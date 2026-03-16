<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request;

use SergeR\RussianPostSDK\Dto\HasKebabCaseSerialization;
use SergeR\RussianPostSDK\Enums\AddressType;

final readonly class Address
{
    use HasKebabCaseSerialization;

    public function __construct(
        public AddressType $addressType,
        public string $postcode,
        public ?string $region = null,
        public ?string $place = null,
        public ?string $location = null,
        public ?string $street = null,
        public ?string $house = null,
        public ?string $room = null,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            addressType: AddressType::tryFrom($data['address-type'] ?? 'DEFAULT') ?? AddressType::DEFAULT,
            postcode: $data['postcode'] ?? '',
            region: $data['region'] ?? null,
            place: $data['place'] ?? null,
            location: $data['location'] ?? null,
            street: $data['street'] ?? null,
            house: $data['house'] ?? null,
            room: $data['room'] ?? null,
        );
    }
}
