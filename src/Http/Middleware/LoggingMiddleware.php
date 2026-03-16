<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Http\Middleware;

use Psr\Http\Message\{RequestInterface, ResponseInterface};
use Psr\Log\LoggerInterface;

final class LoggingMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly LoggerInterface $logger) {}

    public function process(RequestInterface $request, callable $next): ResponseInterface
    {
        $this->logger->info('Russian Post API request', [
            'method' => $request->getMethod(),
            'uri'    => (string)$request->getUri(),
        ]);
        $response = $next($request);
        $this->logger->info('Russian Post API response', [
            'status' => $response->getStatusCode(),
        ]);
        return $response;
    }
}
