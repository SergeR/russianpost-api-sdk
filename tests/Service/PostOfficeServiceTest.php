<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests\Service;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\{Client, Config};
use SergeR\RussianPostSDK\Domain\PostOffice;

final class PostOfficeServiceTest extends TestCase
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

    public function testFindByPostcode(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([
                    ['postal-code' => '119991', 'name' => 'Main PO', 'latitude' => 55.7558, 'longitude' => 37.6173],
                ], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->postOffice()->findByPostcode('119991', 55.7558, 37.6173);

        self::assertIsArray($result);
        if (count($result) > 0) {
            self::assertInstanceOf(PostOffice::class, $result[0]);
        }
    }

    public function testGetServices(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->postOffice()->getServices('119991');

        self::assertIsArray($result);
    }

    public function testFindNearby(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([
                    ['postal-code' => '119991', 'name' => 'Near PO'],
                ], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->postOffice()->findNearby(55.7558, 37.6173);

        self::assertIsArray($result);
        if (count($result) > 0) {
            self::assertInstanceOf(PostOffice::class, $result[0]);
        }
    }
}
