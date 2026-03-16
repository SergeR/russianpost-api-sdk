# Russian Post PHP SDK

> PSR-18 compatible PHP 8.2 SDK for Russian Post (Почта России) JSON API with rich domain models and fluent builders.

[![Tests](https://img.shields.io/badge/tests-46%2F46-brightgreen)](phpunit.xml)
[![PHPStan](https://img.shields.io/badge/phpstan-level%208-brightgreen)](phpstan.neon)
[![Type Coverage](https://img.shields.io/badge/types-100%25-brightgreen)]()
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)

## Features

✨ **Domain-Driven Design**
- Rich domain models (Order, Batch, Archive, PostOffice, TimeSlot)
- Single source of truth for each entity (no separate Request/Response DTOs)
- Fluent `OrderBuilder` for easy order creation with validation

🔒 **Type-Safe**
- PHP 8.2 readonly classes, enums, typed properties
- PHPStan level 8 strict analysis on domain models
- Comprehensive return types

📦 **Full API Coverage**
- 8 service classes (Orders, Batches, Archive, Documents, PostOffice, TimeSlots, Returns, Utilities)
- 30+ API methods
- Binary file support (PDF, ZIP)

✅ **Production Ready**
- PSR-18 (HTTP Client), PSR-7 (HTTP Message), PSR-3 (Logging), PSR-17 (HTTP Factories) compliant
- Comprehensive error handling and error item parsing
- Optional PSR-3 logging middleware
- 46 tests with 109 assertions

## Installation

```bash
composer require serger/russianpost-php-sdk
```

You'll also need a PSR-18 HTTP client and factory implementation:

```bash
composer require guzzlehttp/guzzle
composer require nyholm/psr7
```

## Quick Start

### Basic Setup

```php
<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use SergeR\RussianPostSDK\{Client, Config};

$factory = new Psr17Factory();
$httpClient = new GuzzleClient();

$config = new Config(
    accessToken: 'your-access-token',
    login: 'your-login',
    password: 'your-password',
);

$client = new Client($config, $httpClient, $factory, $factory);
```

### Creating Orders

```php
use SergeR\RussianPostSDK\Domain\OrderBuilder;
use SergeR\RussianPostSDK\Dto\Request\Address;
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory, AddressType};

// Using fluent builder (RECOMMENDED)
$order = OrderBuilder::create()
    ->orderNum('ORDER-12345')
    ->mailType(MailType::ONLINE_PARCEL)
    ->mailCategory(MailCategory::SIMPLE)
    ->mass(1000) // grams
    ->recipient('Doe', 'John') // Sets recipientName, surname, givenName
    ->region('Moscow Oblast')
    ->place('Moscow')
    ->street('Lenin St')
    ->house('10')
    ->addressTo(new Address(
        addressType: AddressType::DEFAULT,
        postcode: '119991'
    ))
    ->phone('+7-999-123-45-67')
    ->email('john@example.com')
    ->fragile(true)
    ->declaredValue(50000) // kopecks
    ->comment('Handle with care')
    ->build();  // ← Validates required fields

// Send to API
$createdOrder = $client->orders()->create($order);
echo "Created order ID: {$createdOrder->id}, Barcode: {$createdOrder->barcode}\n";
```

### Finding Orders

```php
// Find by ID
$order = $client->orders()->findById(123);
echo $order->recipientName;
echo $order->barcode;

// Search by query string
$orders = $client->orders()->find('ORDER-12345');

// Find by group name
$orders = $client->orders()->findByGroup('batch-group-001');
```

### Batch Operations

```php
use SergeR\RussianPostSDK\Domain\Batch;

// Create batch from orders
$batches = $client->batches()->createFromOrders([123, 124, 125]);

// Find batch by name
$batch = $client->batches()->find('batch-2024-001');
echo "Status: {$batch->status->value}";

// Get orders in batch
$orders = $client->batches()->getOrders('batch-2024-001', page: 0, size: 100);

// Check-in batch
$result = $client->batches()->checkin('batch-2024-001');
```

### Post Office & Shipping

```php
use SergeR\RussianPostSDK\Domain\PostOffice;

// Find nearby offices
$offices = $client->postOffice()->findNearby(55.7558, 37.6173);
foreach ($offices as $office) {
    echo "{$office->name} ({$office->postalCode})\n";
    echo "Hours: {$office->workingHours}\n";
}

// Find by zip code
$offices = $client->postOffice()->findByPostcode('119991', 55.7558, 37.6173);

// Time slot booking
$slots = $client->timeSlots()->getAvailable([
    'post-index' => '119991',
    'date' => '2026-03-20',
]);
foreach ($slots as $slot) {
    echo "{$slot->timeStart} - {$slot->timeEnd}\n";
}
```

### Document Download

```php
use SergeR\RussianPostSDK\Enums\{PrintType, PrintSide};

// Download forms as ZIP
$binary = $client->documents()->downloadAll('batch-2024-001', PrintType::THERMAL);
file_put_contents('forms.zip', $binary->content);

// Download Form 7 PDF
$pdf = $client->documents()->f7pdf(123, sendingDate: '2026-03-20');
file_put_contents('form7.pdf', $pdf->content);
```

## Architecture

### Rich Domain Models

The SDK uses **rich domain models** instead of separate Request/Response DTOs:

```php
// ✅ Single Order class for everything
$order = new Order(id: 123, orderNum: 'ORDER-001', ...);
$order = OrderBuilder::create()->orderNum('ORDER-001')->build();
$order = $client->orders()->findById(123);

// ❌ No separate OrderRequest / OrderResponse classes
```

**Benefits:**
- One concept = one class
- Cleaner API surface
- Easier to understand
- Self-documenting

### OrderBuilder Pattern

For creating complex orders with many optional fields:

```php
OrderBuilder::create()
    ->orderNum('ORDER-001')
    ->mailType(MailType::ONLINE_PARCEL)
    // ... chain methods
    ->build()  // Validates required fields
```

**Validation:**
- `orderNum` - required
- `mailType` - required
- `mailCategory` - required
- `mass` - required (grams)
- `recipientName` OR both `surname` + `givenName` - required
- `addressTo` - required

Throws `InvalidArgumentException` if any required field is missing.

### Service Classes

| Service | Methods | Returns |
|---------|---------|---------|
| OrderService | create, createV2, find, findById, update, delete, moveToNew, findByGroup | Order, array<Order> |
| BatchService | create, find, findOrderById, getOrders, checkin, getAll, changeSendingDate, ... | Batch, Order, array |
| ArchiveService | list, moveToArchive, revert, searchLongTerm | Archive, array |
| DocumentService | downloadAll, f7pdf, dictOfErrors, f112pdf, dictList, ... returns BinaryResponse |
| PostOfficeService | findByPostcode, findByAddress, findNearby, getServices, ... | PostOffice, array |
| TimeSlotService | getAvailable, book, confirmBooking, getForRebooking, rebook, cancel | TimeSlot, array |
| ReturnService | create, createWithoutDirect, deleteSeparate, update | array |
| UtilityService | normalizeAddresses, normalizePersons, normalizePhones, calculateTariff, ... | array |

## Type Safety & Analysis

### PHPStan Level 8

Domain models pass strict PHPStan L8 analysis:

```bash
composer stan       # Check domain models (✅ 0 errors)
composer stan:check # Check all files (48 expected errors from HttpTransport polymorphism)
```

Start with strict types on domain models, consider relaxing only where needed (HttpTransport).

### Enums

All API constants are enums for type safety:

```php
use SergeR\RussianPostSDK\Enums\{
    MailType,           // ONLINE_PARCEL, POSTAL_PARCEL, EMS, ...
    MailCategory,       // SIMPLE, WITH_DECLARED_VALUE, ...
    BatchStatus,        // CREATED, SENT, ACCEPTED, RETURNED
    PrintType,          // THERMAL, LASER
    PrintSide,          // ORIGINAL, DUPLICATE, ...
    AddressType,        // DEFAULT, POBOX, ...
    NearbyFilter,       // // All, PARCEL, MAIL, ...
};

$mailType = MailType::ONLINE_PARCEL;
$order->mailType === MailType::ONLINE_PARCEL;  // ✓ Type-safe comparison
```

## Error Handling

```php
use SergeR\RussianPostSDK\Exception\{
    ApiException,         // Base exception
    AuthException,        // 401 Unauthorized
    ValidationException,  // 400/422 with error items
    TransportException,   // Network/HTTP layer errors
};

try {
    $order = $client->orders()->findById(999);
} catch (ValidationException $e) {
    foreach ($e->getErrors() as $error) {
        echo $error->code . ': ' . $error->message . "\n";
    }
} catch (ApiException $e) {
    echo "API Error: {$e->getMessage()}\n";
}
```

## Testing

```bash
# Run tests
composer test

# Run PHPStan (domain models at level 8)
composer stan

# Check all files at level 8 (some expected errors)
composer stan:check
```

**Test Coverage:**
- 46 unit tests
- 109 assertions
- Service mocking with HTTP Mock Client
- Domain model validation tests
- Builder pattern tests

## Development

### Project Structure

```
src/
  Domain/                 # Rich domain models (Order, Batch, Archive, ...)
    OrderBuilder.php      # Fluent builder for Order
  Service/                # API service classes
  Dto/
    Request/             # Sub-DTOs (Address, Dimension, CustomsDeclaration)
  Enums/                 # Type-safe enums (MailType, BatchStatus, ...)
  Exception/             # Exception classes
  Http/                  # HTTP transport layer
  Client.php             # Main SDK client
  Config.php             # Configuration

tests/
  Service/               # Service integration tests
  Domain/                # Domain model tests

phpunit.xml             # Test configuration
phpstan.neon            # Static analysis configuration
EXAMPLES.md             # Detailed usage examples
```

### Adding a New Domain

1. Create `src/Domain/MyDomain.php` (readonly class with `toArray()` and `fromArray()`)
2. Create `src/Service/MyDomainService.php` (service methods)
3. Add to `src/Client.php` (factory method)
4. Create `tests/Domain/MyDomainTest.php` and `tests/Service/MyDomainServiceTest.php`
5. Run `composer test` and `composer stan`

## Requirements

- **PHP**: 8.2+
- **PSR-18**: HTTP Client interface
- **PSR-17**: HTTP Factory interface
- **PSR-7**: HTTP Message interface
- **PSR-3**: Logger interface (optional)

## License

MIT License - see LICENSE file for details

## Resources

- [Russian Post API Documentation](https://otpravka-api.pochta.ru/specification)
- [OpenAPI Specification](openapi.yaml)
- [Usage Examples](EXAMPLES.md)
- [Architecture Notes](ARCHITECTURE.md)

## Support

For issues, questions, or contributions, please open an issue on GitHub.

---

**Built with ❤️ using Domain-Driven Design principles and PHP 8.2 features.**
