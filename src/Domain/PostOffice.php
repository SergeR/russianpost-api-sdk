<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Domain;

/**
 * Доменная модель почтового отделения.
 */
final readonly class PostOffice
{
    public function __construct(
        public ?string $postalCode = null,
        public ?string $name = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?string $address = null,
        public ?string $workingHours = null,
    ) {}

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->postalCode !== null) {
            $data['postal-code'] = $this->postalCode;
        }
        if ($this->name !== null) {
            $data['name'] = $this->name;
        }
        if ($this->latitude !== null) {
            $data['latitude'] = $this->latitude;
        }
        if ($this->longitude !== null) {
            $data['longitude'] = $this->longitude;
        }
        if ($this->address !== null) {
            $data['address'] = $this->address;
        }
        if ($this->workingHours !== null) {
            $data['working-hours'] = $this->workingHours;
        }

        return $data;
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            postalCode: $data['postal-code'] ?? $data['index'] ?? null,
            name: $data['name'] ?? null,
            latitude: isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (float) $data['longitude'] : null,
            address: $data['address'] ?? null,
            workingHours: $data['working-hours'] ?? null,
        );
    }
}
