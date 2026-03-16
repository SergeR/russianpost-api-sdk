<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Http\Middleware;

use Psr\Http\Message\{RequestInterface, ResponseInterface};

interface MiddlewareInterface
{
    /**
     * @param callable(RequestInterface): ResponseInterface $next
     */
    public function process(RequestInterface $request, callable $next): ResponseInterface;
}
