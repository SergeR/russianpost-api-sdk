<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Response\Batch;

use SergeR\RussianPostSDK\Enums\BatchStatus;

final readonly class BatchResponse
{
    public function __construct(
        public string $batchName,
        public ?string $sendingDate = null,
        public ?int $count = null,
        public ?int $weight = null,
        public ?BatchStatus $status = null,
        public ?bool $isLocal = null,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            batchName: $data['batch-name'] ?? $data['name'] ?? '',
            sendingDate: $data['sending-date'] ?? null,
            count: $data['count'] ?? null,
            weight: $data['weight'] ?? null,
            status: isset($data['status']) ? BatchStatus::tryFrom($data['status']) : null,
            isLocal: $data['is-local'] ?? null,
        );
    }
}
