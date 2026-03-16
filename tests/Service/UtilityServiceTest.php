<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests\Service;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\{Client, Config};

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
