# Russian Post PHP SDK - Usage Examples

## Creating an Order

### Using Builder (Recommended)

```php
use SergeR\RussianPostSDK\Client;
use SergeR\RussianPostSDK\Domain\OrderBuilder;
use SergeR\RussianPostSDK\Dto\Request\Address;
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory, AddressType};

// Initialize client
$client = new Client($config, $httpClient, $requestFactory, $streamFactory);

// Create an order using fluent builder
$order = OrderBuilder::create()
    ->orderNum('ORDER-12345')
    ->mailType(MailType::ONLINE_PARCEL)
    ->mailCategory(MailCategory::SIMPLE)
    ->mass(1000) // grams
    ->recipient('Doe', 'John') // Sets recipientName, givenName, surname
    ->region('Moscow Oblast')
    ->place('Moscow')
    ->street('Lenin St')
    ->house('10')
    ->addressTypeTo('DEFAULT')
    ->addressTo(new Address(
        addressType: AddressType::DEFAULT,
        postcode: '119991'
    ))
    ->fragile(true)
    ->phone('+7-999-123-45-67')
    ->email('john@example.com')
    ->declaredValue(50000) // kopecks
    ->comment('Fragile electronics, handle with care')
    ->build();

// Send to API - returns Order with filled ID, barcode, etc
$createdOrder = $client->orders()->create($order);

echo $createdOrder->id;       // API-assigned ID
echo $createdOrder->barcode;  // API-assigned barcode (ШПИ)
```

### Direct Constructor (for minimal orders)

```php
use SergeR\RussianPostSDK\Domain\Order;
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory};

$order = new Order(
    orderNum: 'ORDER-12345',
    mailType: MailType::ONLINE_PARCEL,
    mailCategory: MailCategory::SIMPLE,
    mass: 1000,
);

$createdOrder = $client->orders()->create($order);
```

## Finding Orders

```php
// Find single order by ID
$order = $client->orders()->findById(123);
echo $order->recipientName;
echo $order->mass;
echo $order->barcode;

// Find orders by query string
$orders = $client->orders()->find('ORDER-12345');
foreach ($orders as $order) {
    echo $order->orderNum;
}

// Find by group name
$orders = $client->orders()->findByGroup('my-group');
```

## Working with Batches

```php
use SergeR\RussianPostSDK\Domain\Batch;

// Create batch from orders
$batches = $client->batches()->createFromOrders([123, 124, 125]);

// Find batch by name
$batch = $client->batches()->find('batch-2024-001');
echo $batch->batchName;
echo $batch->status;  // BatchStatus enum
echo $batch->count;   // number of orders

// Get all orders in batch
$orders = $client->batches()->getOrders('batch-2024-001');

// Change sending date
$result = $client->batches()->changeSendingDate('batch-2024-001', 2026, 3, 20);

// Checkin batch
$result = $client->batches()->checkin('batch-2024-001');
```

## PostOffice Operations

```php
use SergeR\RussianPostSDK\Domain\PostOffice;

// Find by postal code with coordinates
$offices = $client->postOffice()->findByPostcode('119991', 55.7558, 37.6173);
foreach ($offices as $office) {
    echo $office->name;           // e.g. "Main Office"
    echo $office->address;
    echo $office->workingHours;
    echo $office->latitude;
    echo $office->longitude;
}

// Find by address
$offices = $client->postOffice()->findByAddress('Red Square, Moscow', top: 5);

// Find nearby offices
$offices = $client->postOffice()->findNearby(55.7558, 37.6173);

// Get services
$services = $client->postOffice()->getServices('119991');
```

## TimeSlot Booking

```php
use SergeR\RussianPostSDK\Domain\TimeSlot;

// Get available time slots
$slots = $client->timeSlots()->getAvailable([
    'post-index' => '119991',
    'date' => '2026-03-20',
]);
foreach ($slots as $slot) {
    echo $slot->date;       // "2026-03-20"
    echo $slot->timeStart;  // "10:00"
    echo $slot->timeEnd;    // "12:00"
    echo $slot->places;     // available slots
}

// Book a time slot
$booking = $client->timeSlots()->book([
    'post-index' => '119991',
    'date' => '2026-03-20',
    'time' => '10:00',
]);

// Get available slots for rebooking
$slots = $client->timeSlots()->getForRebooking('barcode-123', '2026-03-20');
```

## Archive Management

```php
use SergeR\RussianPostSDK\Domain\Archive;

// List archived batches
$archived = $client->archive()->list();
foreach ($archived as $archive) {
    echo $archive->batchName;
    echo $archive->status;
}

// Move batches to archive
$result = $client->archive()->moveToArchive(['batch-1', 'batch-2']);

// Revert from archive
$result = $client->archive()->revert(['batch-1']);

// Search in long-term archive
$results = $client->archive()->searchLongTerm('batch-2024');
```

## Document Download

```php
use SergeR\RussianPostSDK\Dto\Response\BinaryResponse;
use SergeR\RussianPostSDK\Enums\{PrintType, PrintSide};

// Download all forms for batch as ZIP
$binary = $client->documents()->downloadAll('batch-2024-001', PrintType::THERMAL, PrintSide::ORIGINAL);
// $binary is BinaryResponse with media type: application/zip

// Download Form 7 PDF
$pdf = $client->documents()->f7pdf(123, sendingDate: '2026-03-20');
// $pdf is BinaryResponse with media type: application/pdf
```

## Key Differences from Request/Response DTOs

**Before:**
```php
OrderResponse $response = $client->orders()->findById(123);
// Properties clearly response-only, unclear what to send to create()
// CreateOrderRequest needed for create(), different class from response
```

**After:**
```php
// Single Order class for both creating and receiving
Order $order = $client->orders()->findById(123);  // Full response
Order $newOrder = new Order(...);                 // Partial request
Order $created = $client->orders()->create($newOrder);  // Full response
```

This is cleaner and follows DDD principles - Order is ONE concept,
not split between CreateOrderRequest and OrderResponse.
