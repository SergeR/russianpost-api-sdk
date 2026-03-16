# Quick Start Guide

## Installation

```bash
# Install the SDK
composer require serger/russianpost-php-sdk

# Install an HTTP client implementation (choose one)
composer require guzzlehttp/guzzle
composer require nyholm/psr7

# Or use any other PSR-18 client
```

## Basic Usage

```php
<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use SergeR\RussianPostSDK\{Client, Config};

// 1. Create configuration
$config = new Config(
    accessToken: 'your-access-token',
    login: 'your-login@example.com',
    password: 'your-password'
);

// 2. Create HTTP factory and client
$factory = new Psr17Factory();
$httpClient = new GuzzleClient();

// 3. Create SDK client
$client = new Client($config, $httpClient, $factory, $factory);

// 4. Use the SDK
$order = $client->orders()->findById(123);
echo "Order: {$order->recipientName}\n";
```

## Common Operations

### Orders

```php
// Find order by ID
$order = $client->orders()->findById(123);
echo $order->id;

// Search orders
$results = $client->orders()->find('query-string');

// Create order
$order = $client->orders()->create($createOrderRequest);

// Update order
$order = $client->orders()->update(123, $updateRequest);

// Delete orders
$client->orders()->delete([1, 2, 3]);
```

### Batches

```php
// Find batch
$batch = $client->batches()->find('batch-001');

// Create batch from orders
$batch = $client->batches()->createFromOrders([1, 2, 3]);

// Get all batches
$batches = $client->batches()->findAll();

// Checkin batch
$client->batches()->checkin('batch-001');
```

### Documents

```php
// Download label (Form 7)
$pdf = $client->documents()->f7pdf(123);
$pdf->saveAs('/tmp/form7.pdf');

// Download all forms
$zip = $client->documents()->downloadAll('batch-001');
$zip->saveAs('/tmp/forms.zip');
```

### Post Offices

```php
// Find by postcode
$offices = $client->postOffice()->findByPostcode('119991', 55.7558, 37.6173);

// Find nearby
$nearby = $client->postOffice()->findNearby(55.7558, 37.6173);

// Get services
$services = $client->postOffice()->getServices('119991');
```

### Timeslots

```php
// Get available timeslots
$slots = $client->timeSlots()->getAvailable([
    'post-index' => '119991',
    'date' => '2024-03-20'
]);

// Book timeslot
$booking = $client->timeSlots()->book([
    'post-index' => '119991',
    'date' => '2024-03-20',
    'time-start' => '09:00',
    'time-end' => '18:00',
    'barcode' => '12345678901234'
]);
```

### Utilities

```php
// Normalize addresses
$cleaned = $client->utility()->normalizeAddresses([
    ['address' => 'ул. Ленина, 10, Москва']
]);

// Calculate tariff
$tariff = $client->utility()->calculateTariff([
    'mail-type' => 'ONLINE_PARCEL',
    'from-index' => '119991',
    'to-index' => '190000',
    'mass' => 1000
]);

// Get settings
$settings = $client->utility()->getSettings();

// Get API limit
$limit = $client->utility()->getApiLimit();
```

## Creating Orders (Full Example)

```php
use SergeR\RussianPostSDK\Dto\Request\{Order\CreateOrderRequest, Address};
use SergeR\RussianPostSDK\Enums\{AddressType, MailType, MailCategory};

$recipientAddress = new Address(
    addressType: AddressType::DEFAULT,
    postcode: '190000',
    region: 'Санкт-Петербург',
    place: 'Санкт-Петербург',
    street: 'Невский пр.',
    house: '1'
);

$request = new CreateOrderRequest(
    orderNum: 'ORD-' . time(),
    mailType: MailType::ONLINE_PARCEL,
    mailCategory: MailCategory::SIMPLE,
    mass: 500,
    addressTo: $recipientAddress,
    recipientName: 'Иван Иванов',
    givenName: 'Иван',
    surname: 'Иванов',
    region: 'Москва',
    place: 'Москва',
    street: 'ул. Тверская',
    house: '1',
    fragile: false,
    mailDirect: 0,
    tariffCount: 1,
    addressTypeTo: 'DEFAULT'
);

$order = $client->orders()->create($request);
echo "Created order: {$order->id}\n";
```

## Error Handling

```php
use SergeR\RussianPostSDK\Exception\{
    AuthException,
    ValidationException,
    TransportException
};

try {
    $order = $client->orders()->findById(123);
} catch (AuthException $e) {
    // 401 or 403 - authentication failed
    echo "Auth failed: {$e->getMessage()}\n";
} catch (ValidationException $e) {
    // Invalid request or API error
    echo "Validation error (HTTP {$e->statusCode}):\n";
    foreach ($e->getErrors() as $error) {
        echo "  - {$error->code}: {$error->message}\n";
    }
} catch (TransportException $e) {
    // Network error
    echo "Network error: {$e->getMessage()}\n";
}
```

## With Logging

```php
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handlers\StreamHandler;

// Create PSR-3 logger
$logger = new Logger('russian-post');
$logger->pushHandler(new StreamHandler('php://stdout'));

// Pass logger to client
$client = new Client($config, $httpClient, $factory, $factory, $logger);

// All requests/responses will be logged
$order = $client->orders()->findById(123); // Logs request and response
```

## Response Handling

All methods return:
- **Scalar array** - For batch operations: `['key' => 'value', ...]`
- **DTO object** - For single item: `OrderResponse`, `BatchResponse`, etc.
- **BinaryResponse** - For downloads: PDF, ZIP files

```php
// Batch response - array
$result = $client->orders()->delete([1, 2, 3]); // array

// Single response - DTO
$order = $client->orders()->findById(123); // OrderResponse

// Binary response - BinaryResponse
$pdf = $client->documents()->f7pdf(123);
$pdf->saveAs('/tmp/form.pdf');
echo $pdf->mimeType; // 'application/pdf'
```

## Enums

Use enums for type-safe values:

```php
use SergeR\RussianPostSDK\Enums\{
    MailType,
    MailCategory,
    AddressType,
    PrintType
};

$type = MailType::ONLINE_PARCEL;
echo $type->value; // 'ONLINE_PARCEL'

// Enum cases
MailType::POSTAL_PARCEL
MailType::ONLINE_PARCEL
MailType::ONLINE_COURIER
MailType::EMS
MailType::LETTER
// etc.
```

## Tips

1. **Reuse the Client** - Create once, use multiple times
   ```php
   $client = new Client(...); // expensive
   $order1 = $client->orders()->findById(1);
   $order2 = $client->orders()->findById(2); // reuses connection
   ```

2. **Use Enums** - Catch errors at compile time
   ```php
   MailType::tryFrom('INVALID') // null - safe
   MailType::from('INVALID')    // Exception - unsafe
   ```

3. **Error Items** - Each error has code, field (optional), message (optional)
   ```php
   $error = $e->getErrors()[0];
   echo $error->code;      // 'EMPTY_MASS'
   echo $error->field;     // null or 'mass'
   echo $error->message;   // null or error text
   ```

4. **Binary Downloads** - Always check mimeType
   ```php
   $response = $client->documents()->f7pdf(123);
   if (str_starts_with($response->mimeType, 'application/pdf')) {
       file_put_contents('file.pdf', $response->content);
   }
   ```

## Environment Variables

Recommended setup:

```bash
export RUSSIAN_POST_ACCESS_TOKEN="token123"
export RUSSIAN_POST_LOGIN="user@example.com"
export RUSSIAN_POST_PASSWORD="secret"
```

Usage:

```php
$config = new Config(
    accessToken: getenv('RUSSIAN_POST_ACCESS_TOKEN'),
    login: getenv('RUSSIAN_POST_LOGIN'),
    password: getenv('RUSSIAN_POST_PASSWORD')
);
```
