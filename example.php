<?php

/**
 * Example usage of Russian Post PHP SDK
 *
 * This file demonstrates how to use the SDK with a real HTTP client.
 * Replace credentials with actual values.
 */

declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Log\LogLevel;
use SergeR\RussianPostSDK\{Client, Config};
use SergeR\RussianPostSDK\Dto\Request\Order\CreateOrderRequest;
use SergeR\RussianPostSDK\Dto\Request\{Address};
use SergeR\RussianPostSDK\Enums\{AddressType, MailType, MailCategory};

// Setup
$factory = new Psr17Factory();
$httpClient = new GuzzleClient();

$config = new Config(
    accessToken: getenv('RUSSIAN_POST_ACCESS_TOKEN'),
    login: getenv('RUSSIAN_POST_LOGIN'),
    password: getenv('RUSSIAN_POST_PASSWORD'),
    baseUrl: 'https://otpravka-api.pochta.ru',
);

// Create client (with optional logger)
$client = new Client($config, $httpClient, $factory, $factory);

// Example 1: Get user settings
echo "=== Getting user settings ===\n";
try {
    $settings = $client->utility()->getSettings();
    echo json_encode($settings, JSON_PRETTY_PRINT) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 2: Find post offices nearby
echo "\n=== Finding post offices nearby ===\n";
try {
    $offices = $client->postOffice()->findNearby(55.7558, 37.6173);
    echo json_encode($offices, JSON_PRETTY_PRINT) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 3: Create an order
echo "\n=== Creating order ===\n";
try {
    $addressTo = new Address(
        addressType: AddressType::DEFAULT,
        postcode: '119991',
        region: 'Москва',
        place: 'Москва',
        street: 'ул. Ленина',
        house: '10',
    );

    $request = new CreateOrderRequest(
        orderNum: 'ORD-' . time(),
        mailType: MailType::ONLINE_PARCEL,
        mailCategory: MailCategory::SIMPLE,
        mass: 500,
        addressTo: $addressTo,
        recipientName: 'Иван Иванов',
        givenName: 'Иван',
        surname: 'Иванов',
        region: 'Москва',
        place: 'Москва',
        street: 'ул. Ленина',
        house: '20',
        fragile: false,
        mailDirect: 0,
        tariffCount: 1,
        addressTypeTo: 'DEFAULT',
    );

    $order = $client->orders()->create($request);
    echo "Created order ID: {$order->id}\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 4: Get timeslots
echo "\n=== Getting available timeslots ===\n";
try {
    $slots = $client->timeSlots()->getAvailable([
        'post-index' => '119991',
        'date' => date('Y-m-d', strtotime('+1 day')),
    ]);
    echo json_encode($slots, JSON_PRETTY_PRINT) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
