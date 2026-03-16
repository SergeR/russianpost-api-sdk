<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\{RequestFactoryInterface, StreamFactoryInterface, RequestInterface, ResponseInterface};
use SergeR\RussianPostSDK\{Config, Dto\Response\BinaryResponse};
use SergeR\RussianPostSDK\Exception\{TransportException, AuthException, ValidationException, ErrorItem};
use SergeR\RussianPostSDK\Http\Middleware\MiddlewareInterface;

final class HttpTransport
{
    /**
     * @param MiddlewareInterface[] $middleware
     */
    public function __construct(
        private readonly Config $config,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly array $middleware = [],
    ) {}

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed>|null $body
     * @return array<mixed>|BinaryResponse
     */
    public function send(
        string $method,
        string $path,
        array $query = [],
        ?array $body = null,
        bool $expectBinary = false,
    ): array|BinaryResponse {
        // Build URL
        $url = $this->config->baseUrl . $path;
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        // Create request
        $request = $this->requestFactory->createRequest($method, $url);

        // Add headers
        $request = $request
            ->withHeader('Authorization', 'AccessToken ' . $this->config->accessToken)
            ->withHeader('X-User-Authorization', $this->config->userAuthHeader())
            ->withHeader('Content-Type', 'application/json;charset=UTF-8')
            ->withHeader('Accept', 'application/json');

        // Add body if provided
        if ($body !== null) {
            $request = $request->withBody(
                $this->streamFactory->createStream(json_encode($body, JSON_THROW_ON_ERROR))
            );
        }

        // Build middleware pipeline
        $handler = function (RequestInterface $req): ResponseInterface {
            try {
                return $this->httpClient->sendRequest($req);
            } catch (ClientExceptionInterface $e) {
                throw new TransportException('HTTP client error: ' . $e->getMessage(), $e);
            }
        };

        foreach (array_reverse($this->middleware) as $mdw) {
            $next = $handler;
            $handler = fn(RequestInterface $req) => $mdw->process($req, $next);
        }

        // Send request
        $response = $handler($request);

        // Check auth errors
        if ($response->getStatusCode() === 401 || $response->getStatusCode() === 403) {
            throw new AuthException('Authentication failed: ' . $response->getStatusCode());
        }

        // Get response body
        $body = (string)$response->getBody();

        // Handle binary responses
        if ($expectBinary) {
            $contentType = $response->getHeaderLine('Content-Type');
            if (str_starts_with($contentType, 'application/pdf') ||
                str_starts_with($contentType, 'application/zip') ||
                str_starts_with($contentType, 'application/octet-stream')) {
                return new BinaryResponse($body, $contentType);
            }
        }

        // Parse JSON
        try {
            $data = json_decode($body, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new TransportException('Invalid JSON response: ' . $e->getMessage(), $e);
        }

        // Handle errors
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 400) {
            throw self::parseErrors($statusCode, $data);
        }

        // Check for embedded errors in 2xx responses
        if (is_array($data) && isset($data['errors']) && is_array($data['errors']) && !empty($data['errors'])) {
            throw self::parseErrors($statusCode, $data);
        }

        return $data;
    }

    /**
     * Parse API errors and return ValidationException
     */
    private static function parseErrors(int $statusCode, mixed $data): ValidationException
    {
        $errors = [];

        if (!is_array($data)) {
            $errors[] = new ErrorItem('UNKNOWN_ERROR');
            return new ValidationException($statusCode, $errors);
        }

        // Format 1: Array with error-codes objects (endpoints /1.0/, /2.0/)
        if (array_is_list($data) && !empty($data) && isset($data[0]['error-codes'])) {
            foreach ($data as $item) {
                if (isset($item['error-codes']) && is_array($item['error-codes'])) {
                    foreach ($item['error-codes'] as $errorCode) {
                        $errors[] = new ErrorItem(is_string($errorCode) ? $errorCode : $errorCode['error-code'] ?? 'UNKNOWN_ERROR');
                    }
                }
            }
        }
        // Format 2: Single object with 'code' at root level (endpoints /external/v1/)
        elseif (isset($data['code']) && is_string($data['code'])) {
            $errors[] = new ErrorItem(
                $data['code'],
                null,
                $data['message'] ?? null
            );
            // Also parse inner errors if present
            if (isset($data['errors']) && is_array($data['errors'])) {
                foreach ($data['errors'] as $err) {
                    if (is_array($err)) {
                        $errors[] = new ErrorItem(
                            $err['error-code'] ?? $err['code'] ?? 'UNKNOWN_ERROR',
                            $err['field'] ?? null,
                            $err['message'] ?? null
                        );
                    }
                }
            }
        }
        // Format 3: Object with 'errors' key
        elseif (isset($data['errors']) && is_array($data['errors'])) {
            foreach ($data['errors'] as $err) {
                if (is_array($err)) {
                    if (isset($err['error-codes']) && is_array($err['error-codes'])) {
                        foreach ($err['error-codes'] as $errorCode) {
                            $errors[] = new ErrorItem(is_string($errorCode) ? $errorCode : $errorCode['error-code'] ?? 'UNKNOWN_ERROR');
                        }
                    } else {
                        $errors[] = new ErrorItem(
                            $err['error-code'] ?? $err['code'] ?? 'UNKNOWN_ERROR',
                            $err['field'] ?? null,
                            $err['message'] ?? null
                        );
                    }
                }
            }
        }

        // Fallback: raw JSON as code
        if (empty($errors)) {
            $errors[] = new ErrorItem(json_encode($data));
        }

        return new ValidationException($statusCode, $errors, 'API validation error');
    }
}
