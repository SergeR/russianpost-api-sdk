<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Exception;

final readonly class ErrorItem
{
    public function __construct(
        public string $code,
        public ?string $field = null,
        public ?string $message = null,
    ) {}
}
