<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request\TimeSlot;

use SergeR\RussianPostSDK\Dto\HasKebabCaseSerialization;

final readonly class BookingRequest
{
    use HasKebabCaseSerialization;

    public function __construct(
        public string $postIndex,
        public string $date,
        public string $timeStart,
        public string $timeEnd,
        public string $barcode,
        public ?string $contractNumber = null,
        public ?string $uuid = null,
    ) {}
}
