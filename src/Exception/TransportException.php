<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Exception;

use Psr\Http\Client\ClientExceptionInterface;

class TransportException extends ApiException
{
    public function __construct(string $message, ClientExceptionInterface $previous)
    {
        parent::__construct($message, 0, $previous);
    }
}
