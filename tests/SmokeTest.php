<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\{Client, Config};
use SergeR\RussianPostSDK\Dto\Response\Order\OrderResponse;
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory};

final class SmokeTest extends TestCase
{
    public function testBasicClientWorkflow(): void
    {
        $factory = new Psr17Factory();
        $mock = new MockClient();

        $mock->addResponse(
            $factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($factory->createStream(json_encode([
                    'id' => 123,
                    'mail-type' => 'POSTAL_PARCEL',
                    'mail-category' => 'SIMPLE',
                    'recipient-name' => 'Иван Иванов',
                ], JSON_THROW_ON_ERROR)))
        );

        $client = new Client(
            new Config('token123', 'user@example.com', 'secret'),
            $mock,
            $factory,
            $factory,
        );

        $order = $client->orders()->findById(123);

        self::assertInstanceOf(OrderResponse::class, $order);
        self::assertSame(123, $order->id);
        self::assertSame('Иван Иванов', $order->recipientName);
        self::assertSame(MailType::POSTAL_PARCEL, $order->mailType);
        self::assertSame(MailCategory::SIMPLE, $order->mailCategory);
    }

    public function testConfigAuthHeaders(): void
    {
        $config = new Config('token123', 'user@example.com', 'secret');

        self::assertSame('AccessToken token123', 'AccessToken ' . $config->accessToken);
        self::assertSame('Basic ' . base64_encode('user@example.com:secret'), $config->userAuthHeader());
    }

    public function testEnumSerialization(): void
    {
        $mailType = MailType::ONLINE_COURIER;
        $mailCategory = MailCategory::WITH_DECLARED_VALUE;

        self::assertSame('ONLINE_COURIER', $mailType->value);
        self::assertSame('WITH_DECLARED_VALUE', $mailCategory->value);

        self::assertSame(MailType::ONLINE_COURIER, MailType::tryFrom('ONLINE_COURIER'));
        self::assertNull(MailType::tryFrom('INVALID_TYPE'));
    }
}
