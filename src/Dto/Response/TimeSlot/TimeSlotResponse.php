<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Response\TimeSlot;

final readonly class TimeSlotResponse
{
    public function __construct(
        public string $date,
        public string $timeStart,
        public string $timeEnd,
        public ?int $places = null,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'] ?? '',
            timeStart: $data['time-start'] ?? '',
            timeEnd: $data['time-end'] ?? '',
            places: $data['places'] ?? null,
        );
    }
}
