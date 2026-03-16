# SDK Architecture

## Overview

This SDK is structured using a layered architecture with clear separation of concerns:

```
┌─────────────────────────────────┐
│     Application (Your code)     │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│       Client (Main Entry)       │
│  - Lazy-initializes services   │
│  - Aggregates all endpoints    │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│      Service Layer (8x)         │
│  - Domain-specific operations  │
│  - OrderService, BatchService   │
│  - DocumentService, etc.        │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│      HttpTransport Layer        │
│  - HTTP request building        │
│  - Middleware pipeline          │
│  - Error parsing & handling     │
│  - Binary response support      │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│    PSR-18 HTTP Client (User)    │
│    (Guzzle, Symfony, etc.)      │
└─────────────────────────────────┘
```

## Components

### Level 1: Configuration & Client

**`Config.php`**
- Holds API credentials and base URL
- Generates authorization headers
- Configurable timeout

**`Client.php`**
- Main entry point for users
- Lazy-initializes all 8 services
- Optional PSR-3 logger support
- Clean API:
  ```php
  $client->orders()->findById(123)
  $client->batches()->find('batch-001')
  $client->documents()->f7pdf(123)
  ```

### Level 2: Service Layer

**8 Domain Services:**
- `OrderService` - Order CRUD and search
- `BatchService` - Batch management
- `ArchiveService` - Archive operations
- `DocumentService` - Form/label generation
- `PostOfficeService` - Post office info
- `TimeSlotService` - Timeslot booking
- `ReturnService` - Return management
- `UtilityService` - Utility operations

Each service:
- Depends only on `HttpTransport`
- Converts HTTP responses to DTOs
- Handles domain-specific logic

### Level 3: HTTP Transport & Middleware

**`HttpTransport.php`**
- Builds PSR-7 requests
- Adds authentication headers
- Constructs middleware pipeline
- Detects and parses API errors (2 formats)
- Returns typed responses or `BinaryResponse`

**`MiddlewareInterface.php`**
- Allows request/response interception
- Implements PSR chain of responsibility

**`LoggingMiddleware.php`**
- Optional PSR-3 logging
- Only enabled if logger provided

### Level 4: Data Transfer Objects

**Request DTOs**
- `CreateOrderRequest` - Order creation
- `BookingRequest` - Timeslot booking
- Use `HasKebabCaseSerialization` trait
- Automatically convert camelCase → kebab-case in JSON

**Response DTOs**
- `OrderResponse`, `BatchResponse`, etc.
- All have `static fromArray(array $data): self`
- Parse kebab-case JSON to camelCase PHP
- Use `tryFrom()` for enum hydration (safe)

### Level 5: Exception Hierarchy

```
Exception
├── ApiException (base)
│   ├── AuthException (401/403)
│   ├── TransportException (PSR-18 errors)
│   └── ValidationException (API errors)
```

`ValidationException.php`
- Stores HTTP status code
- Contains array of `ErrorItem[]`
- Each ErrorItem has: code, field (optional), message (optional)

### Level 6: Enums

Strong typing with PHP 8.1 enums:
- `AddressType` - Address types
- `MailCategory` - Shipment categories
- `MailType` - Shipment types
- `BatchStatus` - Batch states
- `PrintType` - Printer type
- `PrintSide` - Print orientation
- `NearbyFilter` - Office filters

## Data Flow

### Typical Request Flow

```
User Code
    ↓ client->orders()->findById(123)
OrderService.findById()
    ↓ transport.send('GET', '/1.0/backlog/123')
HttpTransport.send()
    ↓ build request + headers + middleware pipeline
Middleware Chain
    ↓ (optional logging)
PSR-18 HTTP Client
    ↓ (Guzzle, Symfony, etc. - user provides)
Russian Post API
    ↓ HTTP response
HttpTransport
    ↓ parse JSON + check for errors
Response/Error Handling
    ↓ BinaryResponse or array or throw exception
OrderService
    ↓ OrderResponse::fromArray($data)
OrderResponse DTO
    ↓
User Code
```

### Error Flow

Two distinct API error formats are auto-detected:

**Format 1: `/1.0/` and `/2.0/` endpoints**
```json
[{"error-codes": [{"error-code": "CODE", "position": 0}]}]
```

**Format 2: `/external/v1/` endpoints (timeslots)**
```json
{"code": "CODE", "message": "...", "errors": [...]}
```

Both are parsed into:
```php
ValidationException {
    statusCode: 422,
    errors: [
        ErrorItem { code, field?, message? },
        ...
    ]
}
```

## Design Patterns

### 1. Lazy Initialization
```php
// Services created only when first accessed
private ?OrderService $orders = null;

public function orders(): OrderService {
    return $this->orders ??= new OrderService($this->transport);
}
```

### 2. Middleware Chain of Responsibility
```php
// Wrap each middleware in reverse order
foreach (array_reverse($middleware) as $mdw) {
    $next = $handler;
    $handler = fn($req) => $mdw->process($req, $next);
}
```

### 3. DTO with Trait-based Serialization
```php
final readonly class Address {
    use HasKebabCaseSerialization;

    public function __construct(
        public AddressType $addressType,
        public string $postcode,
    ) {}
}

// Automatically: addressType → "address-type" in JSON
```

### 4. Safe Enum Hydration
```php
// Never crash on unknown enum values
MailType::tryFrom($data['mail-type']) // Returns enum or null
```

## PSR Compliance

| PSR | Name | Usage |
|-----|------|-------|
| PSR-7 | HTTP Message | Request/Response objects |
| PSR-17 | HTTP Factories | Create Request/Stream |
| PSR-18 | HTTP Client | Send requests |
| PSR-3 | Logger | Optional logging middleware |

## Testing Strategy

Tests use mock HTTP client to verify:
1. Correct endpoints are called
2. Query parameters are passed
3. Request bodies are correct
4. Responses are properly hydrated
5. Errors are parsed correctly

```php
$mock = new MockClient();
$mock->addResponse($response);
$client = new Client($config, $mock, $factory, $factory);
```

## Error Recovery

The SDK does NOT attempt to:
- Retry failed requests
- Cache responses
- Validate API tokens
- Handle rate limiting

These are user responsibilities:

```php
try {
    // User code should handle retries, caching, validation
    for ($i = 0; $i < 3; $i++) {
        try {
            return $client->orders()->findById(123);
        } catch (ApiException $e) {
            if ($i === 2) throw $e;
            sleep(pow(2, $i));
        }
    }
} catch (AuthException $e) {
    // Handle authentication
} catch (ValidationException $e) {
    // Handle validation errors
}
```

## Extensibility

### Custom Middleware

```php
class CustomMiddleware implements MiddlewareInterface {
    public function process(RequestInterface $request, callable $next): ResponseInterface {
        // Modify request
        $response = $next($request);
        // Modify response
        return $response;
    }
}

$middleware = [new CustomMiddleware()];
$transport = new HttpTransport($config, $httpClient, $factory, $factory, $middleware);
```

### Custom HTTP Client

Any PSR-18 client implementation works:

```php
$httpClient = new GuzzleClient();
// or
$httpClient = new SymfonyHttpClient();
// or
$httpClient = new CustomPSR18Client();

$client = new Client($config, $httpClient, ...);
```

## Future Enhancements

Potential additions without breaking the API:
- Response caching middleware
- Request retry middleware
- Request signing middleware
- Response compression middleware
- Request validation middleware
- Custom object hydration strategies
