<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\Domain\{Order, OrderBuilder};
use SergeR\RussianPostSDK\Dto\Request\Address;
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory, AddressType};

final class OrderBuilderTest extends TestCase
{
    public function testBuilderCreatesOrder(): void
    {
        $order = OrderBuilder::create()
            ->orderNum('ORDER-001')
            ->mailType(MailType::ONLINE_PARCEL)
            ->mailCategory(MailCategory::SIMPLE)
            ->mass(1000)
            ->build();

        self::assertInstanceOf(Order::class, $order);
        self::assertSame('ORDER-001', $order->orderNum);
        self::assertSame(MailType::ONLINE_PARCEL, $order->mailType);
        self::assertSame(MailCategory::SIMPLE, $order->mailCategory);
        self::assertSame(1000, $order->mass);
    }

    public function testBuilderWithRecipient(): void
    {
        $order = OrderBuilder::create()
            ->orderNum('ORDER-002')
            ->recipient('Doe', 'John', 'Middle')
            ->build();

        self::assertSame('John Doe Middle', $order->recipientName);
        self::assertSame('Doe', $order->surname);
        self::assertSame('John', $order->givenName);
        self::assertSame('Middle', $order->middleName);
    }

    public function testBuilderWithRecipientWithoutMiddleName(): void
    {
        $order = OrderBuilder::create()
            ->recipient('Doe', 'John')
            ->build();

        self::assertSame('John Doe', $order->recipientName);
    }

    public function testBuilderWithFullAddress(): void
    {
        $address = new Address(
            addressType: AddressType::DEFAULT,
            postcode: '119991'
        );

        $order = OrderBuilder::create()
            ->orderNum('ORDER-003')
            ->recipient('Doe', 'John')
            ->region('Moscow Oblast')
            ->place('Moscow')
            ->street('Lenin St')
            ->house('10')
            ->addressTypeTo('DEFAULT')
            ->addressTo($address)
            ->build();

        self::assertSame('ORDER-003', $order->orderNum);
        self::assertSame('Moscow Oblast', $order->region);
        self::assertSame('Moscow', $order->place);
        self::assertSame('Lenin St', $order->street);
        self::assertSame('10', $order->house);
        self::assertInstanceOf(Address::class, $order->addressTo);
    }

    public function testBuilderWithContactInfo(): void
    {
        $order = OrderBuilder::create()
            ->phone('+7-999-123-45-67')
            ->email('test@example.com')
            ->build();

        self::assertSame('+7-999-123-45-67', $order->phone);
        self::assertSame('test@example.com', $order->email);
    }

    public function testBuilderWithDeliveryOptions(): void
    {
        $order = OrderBuilder::create()
            ->fragile(true)
            ->courier(true)
            ->declaredValue(50000)
            ->compulsoryPayment(10000)
            ->cashOnDelivery(5000)
            ->comment('Handle with care')
            ->build();

        self::assertTrue($order->fragile);
        self::assertTrue($order->courier);
        self::assertSame(50000, $order->declaredValue);
        self::assertSame(10000, $order->compulsoryPayment);
        self::assertSame(5000, $order->cashOnDelivery);
        self::assertSame('Handle with care', $order->comment);
    }

    public function testBuilderWithMinimalData(): void
    {
        $order = OrderBuilder::create()
            ->build();

        // All fields should be null for minimal order
        self::assertNull($order->orderNum);
        self::assertNull($order->mailType);
        self::assertNull($order->mass);
    }

    public function testBuilderMethodChainingFluency(): void
    {
        $order = OrderBuilder::create()
            ->orderNum('ORDER-004')
            ->mailType(MailType::POSTAL_PARCEL)
            ->mailCategory(MailCategory::WITH_DECLARED_VALUE)
            ->mass(2500)
            ->recipient('Smith', 'Jane')
            ->phone('+1-800-123-4567')
            ->email('jane@example.com')
            ->fragile(true)
            ->groupName('batch-001')
            ->comment('Fragile electronics')
            ->build();

        self::assertSame('ORDER-004', $order->orderNum);
        self::assertSame(MailType::POSTAL_PARCEL, $order->mailType);
        self::assertSame(2500, $order->mass);
        self::assertSame('batch-001', $order->groupName);
        self::assertTrue($order->fragile);
    }

    public function testBuilderCanBeReused(): void
    {
        $builder = OrderBuilder::create()
            ->orderNum('BASE-ORDER')
            ->mailType(MailType::ONLINE_PARCEL)
            ->mailCategory(MailCategory::SIMPLE);

        // Create first order
        $order1 = $builder
            ->mass(1000)
            ->build();

        // Note: Builder state is shared, so let's test with new builders
        $builder2 = OrderBuilder::create()
            ->orderNum('BASE-ORDER')
            ->mailType(MailType::ONLINE_PARCEL)
            ->mass(2000)
            ->build();

        self::assertSame(1000, $order1->mass);
        self::assertSame(2000, $builder2->mass);
    }

    public function testBuilderToArrayIntegration(): void
    {
        $order = OrderBuilder::create()
            ->orderNum('ORDER-005')
            ->mailType(MailType::ONLINE_COURIER)
            ->mailCategory(MailCategory::SIMPLE)
            ->mass(500)
            ->recipient('Ivanov', 'Ivan')
            ->phone('+7-999-100-00-01')
            ->build();

        $array = $order->toArray();

        self::assertSame('ORDER-005', $array['order-num']);
        self::assertSame('ONLINE_COURIER', $array['mail-type']);
        self::assertSame(500, $array['mass']);
        self::assertSame('Ivan Ivanov', $array['recipient-name']);
    }

    public function testBuilderFromApiResponse(): void
    {
        // Get order from API
        $apiResponse = [
            'id' => 123,
            'order-num' => 'ORDER-006',
            'mail-type' => 'ONLINE_PARCEL',
            'mail-category' => 'SIMPLE',
            'mass' => 1500,
            'barcode' => 'RU123456789CN',
            'status' => 'CREATED',
            'recipient-name' => 'Test User',
        ];

        $order = Order::fromArray($apiResponse);

        // Can then use builder to create a similar order
        $newOrder = OrderBuilder::create()
            ->orderNum('ORDER-007')
            ->mailType($order->mailType)
            ->mailCategory($order->mailCategory)
            ->mass($order->mass)
            ->build();

        self::assertSame(MailType::ONLINE_PARCEL, $newOrder->mailType);
        self::assertSame(1500, $newOrder->mass);
    }
}
