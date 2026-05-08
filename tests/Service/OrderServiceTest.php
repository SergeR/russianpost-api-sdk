<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests\Service;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\{Client, Config};
use SergeR\RussianPostSDK\Domain\Order;
use SergeR\RussianPostSDK\Dto\Request\Address;
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory, AddressType};
use SergeR\RussianPostSDK\Exception\{AuthException, ValidationException};

final class OrderServiceTest extends TestCase
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

    public function testFindByIdSendsGetRequest(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([
                    'id' => 123,
                    'mail-type' => 'ONLINE_PARCEL',
                    'mail-category' => 'SIMPLE',
                    'recipient-name' => 'Иван Иванов',
                    'mass' => 500,
                ], JSON_THROW_ON_ERROR)))
        );

        $order = $this->client->orders()->findById(123);

        self::assertInstanceOf(Order::class, $order);
        self::assertSame(123, $order->id);
        self::assertSame('Иван Иванов', $order->recipientName);
        self::assertSame(500, $order->mass);
        self::assertSame('ONLINE_PARCEL', $order->mailType);
        self::assertSame('SIMPLE', $order->mailCategory);
        self::assertSame(MailType::ONLINE_PARCEL, $order->getMailType());
        self::assertSame(MailCategory::SIMPLE, $order->getMailCategory());
    }

    public function testCreateSendsPutRequest(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([
                    'id' => 456,
                    'mail-type' => 'POSTAL_PARCEL',
                ], JSON_THROW_ON_ERROR)))
        );

        $order = new Order(
            orderNum: 'ORDER-001',
            mailType: MailType::POSTAL_PARCEL->value,
            mailCategory: MailCategory::SIMPLE->value,
            mass: 1000,
            addressTo: $this->createMockAddress(),
            recipientName: 'Test',
            givenName: 'Test',
            surname: 'User',
            region: 'Moscow',
            place: 'Moscow',
            street: 'Lenina',
            house: '10',
            fragile: false,
            mailDirect: 0,
            tariffCount: 1,
            addressTypeTo: 'DEFAULT',
        );

        $result = $this->client->orders()->create($order);

        self::assertSame(456, $result->id);
        self::assertSame('POSTAL_PARCEL', $result->mailType);
    }

    public function testDeleteSendsDeleteRequest(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->orders()->delete([1, 2, 3]);

        self::assertIsArray($result);
    }

    public function testAuthException401(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(401)
                ->withHeader('Content-Type', 'application/json')
        );

        self::expectException(AuthException::class);
        $this->client->orders()->findById(123);
    }

    public function testValidationExceptionOnErrorResponse(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(400)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([
                    ['error-codes' => [['error-code' => 'INVALID_REQUEST']]]
                ], JSON_THROW_ON_ERROR)))
        );

        self::expectException(ValidationException::class);
        self::expectExceptionMessage('API validation error');

        try {
            $this->client->orders()->findById(123);
        } catch (ValidationException $e) {
            self::assertCount(1, $e->getErrors());
            self::assertSame('INVALID_REQUEST', $e->getErrors()[0]->code);
            throw $e;
        }
    }

    private function createMockAddress(): Address
    {
        return new Address(
            addressType: AddressType::DEFAULT,
            postcode: '119991'
        );
    }
}
