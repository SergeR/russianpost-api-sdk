<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests\Service;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\{Client, Config};
use SergeR\RussianPostSDK\Dto\Response\Batch\BatchResponse;

final class BatchServiceTest extends TestCase
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

    public function testFindBatchByName(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([
                    'batch-name' => 'batch-001',
                    'sending-date' => '2024-03-17',
                    'count' => 5,
                    'weight' => 1500,
                    'status' => 'CREATED',
                ], JSON_THROW_ON_ERROR)))
        );

        $batch = $this->client->batches()->find('batch-001');

        self::assertInstanceOf(BatchResponse::class, $batch);
        self::assertSame('batch-001', $batch->batchName);
        self::assertSame(5, $batch->count);
    }

    public function testCreateFromOrders(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->batches()->createFromOrders([1, 2, 3]);

        self::assertIsArray($result);
    }

    public function testGetOrdersWithPagination(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->batches()->getOrders('batch-001', page: 0, size: 20);

        self::assertIsArray($result);
    }
}
