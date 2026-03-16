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

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            height: (int) ($data['height'] ?? 0),
            length: (int) ($data['length'] ?? 0),
            width: (int) ($data['width'] ?? 0),
        );
    }
}
