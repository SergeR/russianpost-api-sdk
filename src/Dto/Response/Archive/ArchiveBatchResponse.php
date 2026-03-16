<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Response\Archive;

final readonly class ArchiveBatchResponse
{
    public function __construct(
        public string $batchName,
        public ?string $sendingDate = null,
        public ?int $count = null,
        public ?int $weight = null,
        public ?string $status = null,
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
            status: $data['status'] ?? null,
        );
    }
}
