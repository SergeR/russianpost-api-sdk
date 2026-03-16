<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Response\PostOffice;

final readonly class PostOfficeResponse
{
    public function __construct(
        public string $postalCode,
        public ?string $name = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?string $address = null,
        public ?string $workingHours = null,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            postalCode: $data['postal-code'] ?? $data['index'] ?? '',
            name: $data['name'] ?? null,
            latitude: isset($data['latitude']) ? (float)$data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float)$data['longitude'] : null,
            address: $data['address'] ?? null,
            workingHours: $data['working-hours'] ?? null,
        );
    }
}
