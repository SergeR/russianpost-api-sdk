<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Domain;

/**
 * Доменная модель временного слота.
 */
final readonly class TimeSlot
{
    public function __construct(
        public ?string $date = null,
        public ?string $timeStart = null,
        public ?string $timeEnd = null,
        public ?int $places = null,
    ) {}

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->date !== null) {
            $data['date'] = $this->date;
        }
        if ($this->timeStart !== null) {
            $data['time-start'] = $this->timeStart;
        }
        if ($this->timeEnd !== null) {
            $data['time-end'] = $this->timeEnd;
        }
        if ($this->places !== null) {
            $data['places'] = $this->places;
        }

        return $data;
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'] ?? null,
            timeStart: $data['time-start'] ?? null,
            timeEnd: $data['time-end'] ?? null,
            places: $data['places'] ?? null,
        );
    }
}
