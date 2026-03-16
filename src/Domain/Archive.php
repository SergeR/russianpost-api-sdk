<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Domain;

/**
 * Доменная модель архивированной партии.
 */
final readonly class Archive
{
    public function __construct(
        public ?string $batchName = null,
        public ?string $sendingDate = null,
        public ?int $count = null,
        public ?int $weight = null,
        public ?string $status = null,
    ) {}

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->batchName !== null) {
            $data['batch-name'] = $this->batchName;
        }
        if ($this->sendingDate !== null) {
            $data['sending-date'] = $this->sendingDate;
        }
        if ($this->count !== null) {
            $data['count'] = $this->count;
        }
        if ($this->weight !== null) {
            $data['weight'] = $this->weight;
        }
        if ($this->status !== null) {
            $data['status'] = $this->status;
        }

        return $data;
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            batchName: $data['batch-name'] ?? $data['name'] ?? null,
            sendingDate: $data['sending-date'] ?? null,
            count: $data['count'] ?? null,
            weight: $data['weight'] ?? null,
            status: $data['status'] ?? null,
        );
    }
}
