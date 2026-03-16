<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request;

use SergeR\RussianPostSDK\Dto\HasKebabCaseSerialization;

final readonly class Dimension
{
    use HasKebabCaseSerialization;

    public function __construct(
        public int $height,   // millimeters
        public int $length,   // millimeters
        public int $width,    // millimeters
    ) {}
}
