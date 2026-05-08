<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests\Service;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\{Client, Config};
use SergeR\RussianPostSDK\Domain\ShippingPoint;
use SergeR\RussianPostSDK\Enums\ReturnAddressType;

final class UtilityServiceTest extends TestCase
{
    private Psr17Factory $factory;
    private MockClient $mockClient;
    private Client $client;

    protected function setUp(): void
    {
        $this->factory = new Psr17Factory();
        $this->mockClient = new MockClient();

        $config = new Config('token123', 'user@example.com', 'secret');
        $this->client = new Client(
            $config,
            $this->mockClient,
            $this->factory,
            $this->factory,
        );
    }

    public function testNormalizeAddresses(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->utility()->normalizeAddresses([]);

        self::assertIsArray($result);
    }

    public function testCalculateTariff(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->utility()->calculateTariff([]);

        self::assertIsArray($result);
    }

    public function testGetSettings(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->utility()->getSettings();

        self::assertIsArray($result);
    }

    public function testGetShippingPoints(): void
    {
        $payload = [
            [
                'operator-postcode' => '125040',
                'additional-operator-postcode' => '800000',
                'additional-operator-postcodes' => ['800000'],
                'available-mail-types' => ['LETTER_CLASS_1', 'EMS', 'ONLINE_PARCEL'],
                'available-products' => [
                    [
                        'mail-category' => 'ORDINARY',
                        'mail-type' => 'EMS',
                        'product-type' => 'EMS_ORDINARY',
                    ],
                ],
                'courier-call' => false,
                'enabled' => true,
                'is-agreement' => true,
                'ops-address' => 'пр-кт Ленинградский, д.23, г Москва',
                'pre-postal-preparation' => false,
                'return-address-type' => 'POSTOFFICE_ADDRESS',
                'temporary' => false,
                'user-available-mail-types' => ['LETTER_CLASS_1', 'EMS'],
                'user-available-products' => [],
                'user-return-address' => ['manual-address-input' => false],
                'vsd-enabled' => true,
            ],
        ];

        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode($payload, JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->utility()->getShippingPoints();

        self::assertCount(1, $result);
        self::assertInstanceOf(ShippingPoint::class, $result[0]);
        self::assertSame('125040', $result[0]->operatorPostcode);
        self::assertSame('800000', $result[0]->additionalOperatorPostcode);
        self::assertSame(['800000'], $result[0]->additionalOperatorPostcodes);
        self::assertSame(['LETTER_CLASS_1', 'EMS', 'ONLINE_PARCEL'], $result[0]->availableMailTypes);
        self::assertFalse($result[0]->courierCall);
        self::assertTrue($result[0]->enabled);
        self::assertTrue($result[0]->isAgreement);
        self::assertSame('пр-кт Ленинградский, д.23, г Москва', $result[0]->opsAddress);
        self::assertFalse($result[0]->prePostalPreparation);
        self::assertSame(ReturnAddressType::POSTOFFICE_ADDRESS, $result[0]->returnAddressType);
        self::assertFalse($result[0]->temporary);
        self::assertTrue($result[0]->vsdEnabled);
    }

    public function testGetApiLimit(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->utility()->getApiLimit();

        self::assertIsArray($result);
    }
}
