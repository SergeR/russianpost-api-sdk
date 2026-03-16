# Russian Post PHP SDK

PSR-18 compatible PHP 8.2 SDK for the Russian Post (Почта России) JSON API.

## Installation

```bash
composer require serger/russianpost-php-sdk
```

You'll also need a PSR-18 HTTP client implementation:

```bash
composer require guzzlehttp/guzzle
composer require nyholm/psr7
```

## Quick Start

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

// Find order by ID
$order = $client->orders()->findById(123);
echo "Order ID: {$order->id}, Recipient: {$order->recipientName}\n";

// Create a batch from orders
$batch = $client->batches()->createFromOrders([1, 2, 3]);

// Find post offices nearby
$offices = $client->postOffice()->findNearby(55.7558, 37.6173);
```

## Features

- **8 Service Classes**: Orders, Batches, Archive, Documents, PostOffice, TimeSlots, Returns, Utilities
- **Type-Safe**: Comprehensive use of PHP 8.2 features (readonly classes, enums, typed properties)
- **PSR Compliant**: Uses PSR-18 (HTTP Client), PSR-7 (HTTP Message), PSR-3 (Logging), PSR-17 (HTTP Factories)
- **Binary Support**: Built-in support for PDF/ZIP downloads
- **Error Handling**: Comprehensive error parsing for both API error formats
- **Logging**: Optional PSR-3 logging middleware

## Testing

```bash
composer test
composer stan
```

## License

MIT
