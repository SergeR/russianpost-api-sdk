<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests\Service;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\{Client, Config};
use SergeR\RussianPostSDK\Domain\TimeSlot;
use SergeR\RussianPostSDK\Exception\ValidationException;

final class TimeSlotServiceTest extends TestCase
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

    public function testGetAvailable(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([
                    ['date' => '2026-03-20', 'time-start' => '10:00', 'time-end' => '12:00'],
                ], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->timeSlots()->getAvailable(['post-index' => '119991']);

        self::assertIsArray($result);
        if (count($result) > 0) {
            self::assertInstanceOf(TimeSlot::class, $result[0]);
        }
    }

    public function testBook(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
        );

        $result = $this->client->timeSlots()->book(['post-index' => '119991']);

        self::assertIsArray($result);
    }

    public function testTimeSlotNotFoundError(): void
    {
        $this->mockClient->addResponse(
            $this->factory->createResponse(404)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->factory->createStream(json_encode([
                    'code' => 'TIMESLOT_NOT_FOUND',
                    'message' => 'Timeslot not found',
                ], JSON_THROW_ON_ERROR)))
        );

        self::expectException(ValidationException::class);

        try {
            $this->client->timeSlots()->getAvailable(['post-index' => '999999']);
        } catch (ValidationException $e) {
            self::assertCount(1, $e->getErrors());
            self::assertSame('TIMESLOT_NOT_FOUND', $e->getErrors()[0]->code);
            throw $e;
        }
    }
}
