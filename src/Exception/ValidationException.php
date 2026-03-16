<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Exception;

final class ValidationException extends ApiException
{
    /**
     * @param ErrorItem[] $errors
     */
    public function __construct(
        public readonly int $statusCode,
        private readonly array $errors,
        string $message = '',
    ) {
        parent::__construct($message);
    }

    /**
     * @return ErrorItem[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
