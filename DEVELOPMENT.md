# Development Guide

## Setup

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up environment variables:
   ```bash
   export RUSSIAN_POST_ACCESS_TOKEN="your-token"
   export RUSSIAN_POST_LOGIN="your-login"
   export RUSSIAN_POST_PASSWORD="your-password"
   ```

## Project Structure

```
src/
├── Client.php                 # Main entry point
├── Config.php                 # Configuration holder
├── Dto/                       # Data Transfer Objects
│   ├── HasKebabCaseSerialization.php  # Trait for DTO serialization
│   ├── Request/               # Request DTOs
│   └── Response/              # Response DTOs
├── Enums/                     # Enum definitions
├── Exception/                 # Exception classes
├── Http/                      # HTTP transport layer
│   ├── HttpTransport.php      # Main HTTP transport
│   └── Middleware/            # Middleware implementations
├── Service/                   # Service classes (one per domain)
│
tests/
├── Service/                   # Service unit tests
└── SmokeTest.php             # Integration smoke test
```

## Services

Each service corresponds to a domain in the Russian Post API:

- **OrderService** - Order management
- **BatchService** - Batch/shipment operations
- **ArchiveService** - Archive management
- **DocumentService** - Document generation (forms, labels)
- **PostOfficeService** - Post office information
- **TimeSlotService** - Timeslot booking
- **ReturnService** - Return management
- **UtilityService** - Utility operations (address normalization, tariffs)

## Testing

### Run all tests
```bash
composer test
```

### Run specific test
```bash
vendor/bin/phpunit --filter OrderServiceTest
```

### Static analysis
```bash
composer stan
```

## Code Style

The project follows:
- PSR-12 coding standard
- Strict types declaration
- Type hints on all method parameters and return values

## Adding New Endpoints

1. Add Request/Response DTOs in `src/Dto/Request|Response/` if needed
2. Add method to appropriate service
3. Add test case in `tests/Service/YourServiceTest.php`
4. Run tests and static analysis

### Example: Adding a method to OrderService

```php
// In src/Service/OrderService.php
public function search(string $query): array
{
    return $this->transport->send('GET', '/1.0/orders/search', query: ['q' => $query]);
}

// In tests/Service/OrderServiceTest.php
public function testSearch(): void
{
    $this->mockClient->addResponse(
        $this->factory->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->factory->createStream(json_encode([], JSON_THROW_ON_ERROR)))
    );

    $result = $this->client->orders()->search('test');

    self::assertIsArray($result);
}
```

## Dependencies

- **psr/http-client** - PSR-18 HTTP client interface
- **psr/http-message** - PSR-7 HTTP message interface
- **psr/http-factory** - PSR-17 HTTP factory interface
- **psr/log** - PSR-3 logging interface

### Dev Dependencies

- **phpunit/phpunit** - Testing framework
- **nyholm/psr7** - PSR-7 implementation for tests
- **php-http/mock-client** - Mock HTTP client for tests
- **phpstan/phpstan** - Static analysis

## Error Handling

The SDK recognizes two API error formats:

### Format 1: `/1.0/` and `/2.0/` endpoints
```json
[{"error-codes": [{"error-code": "...", "position": 0}]}]
```

### Format 2: `/external/v1/` endpoints
```json
{"code": "ERROR_CODE", "message": "...", "errors": [...]}
```

Both are converted to `ValidationException` with `getErrors()` returning `ErrorItem[]`.

## Logging

Pass a PSR-3 logger to enable request/response logging:

```php
$logger = new MyLogger();
$client = new Client($config, $httpClient, $factory, $factory, $logger);
```

## Binary Responses

Methods that return binary data (PDFs, ZIPs) return `BinaryResponse` objects:

```php
$pdf = $client->documents()->f7pdf(123);
$pdf->saveAs('/tmp/form.pdf');  // Save to disk
echo $pdf->mimeType;             // 'application/pdf'
```
