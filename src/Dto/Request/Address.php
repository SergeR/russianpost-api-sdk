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
}
