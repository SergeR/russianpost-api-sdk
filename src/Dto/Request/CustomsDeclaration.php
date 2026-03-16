<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request;

use SergeR\RussianPostSDK\Dto\HasKebabCaseSerialization;

final readonly class CustomsDeclaration
{
    use HasKebabCaseSerialization;

    /**
     * @param array<array{amount: int, country-code?: int, description: string, tnved-code: string, trademark?: string, value: int, weight?: int}> $customsEntries
     */
    public function __construct(
        public string $currency,
        public array $customsEntries,
        public string $entriesType,
        public ?string $certificateNumber = null,
        public ?string $customsCode = null,
    ) {}
}
