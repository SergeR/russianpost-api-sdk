<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Domain\PostOffice;
use SergeR\RussianPostSDK\Dto\Response\BinaryResponse;
use SergeR\RussianPostSDK\Http\HttpTransport;

final class PostOfficeService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * Find post offices by postcode
     * @param array<string,mixed> $options
     * @return array<PostOffice>
     */
    public function findByPostcode(string $postalCode, float $lat, float $lng, array $options = []): array
    {
        $query = ['lat' => $lat, 'lng' => $lng, ...$options];
        $response = $this->transport->send('GET', "/postoffice/1.0/{$postalCode}", query: $query);
        return array_map(static fn(array $item) => PostOffice::fromArray($item), $response);
    }

    /**
     * Find post offices by address
     * @return array<PostOffice>
     */
    public function findByAddress(string $address, int $top = 3): array
    {
        $response = $this->transport->send('GET', '/postoffice/1.0/by-address', query: [
            'address' => $address,
            'top' => $top,
        ]);
        return array_map(static fn(array $item) => PostOffice::fromArray($item), $response);
    }

    /**
     * Get services for post office
     * @return array<mixed>
     */
    public function getServices(string $postalCode): array
    {
        return $this->transport->send('GET', "/postoffice/1.0/{$postalCode}/services");
    }

    /**
     * Get services by group
     * @return array<mixed>
     */
    public function getServicesByGroup(string $postalCode, string $serviceGroupId): array
    {
        return $this->transport->send('GET', "/postoffice/1.0/{$postalCode}/services/{$serviceGroupId}");
    }

    /**
     * Find nearby post offices
     * @param array<string,mixed> $options
     * @return array<PostOffice>
     */
    public function findNearby(float $lat, float $lng, array $options = []): array
    {
        $query = ['lat' => $lat, 'lng' => $lng, ...$options];
        $response = $this->transport->send('GET', '/postoffice/1.0/nearby', query: $query);
        return array_map(static fn(array $item) => PostOffice::fromArray($item), $response);
    }

    /**
     * Get settlement codes
     * @return array<mixed>
     */
    public function getSettlementCodes(string $settlement, ?string $region = null, ?string $district = null): array
    {
        $query = ['settlement' => $settlement];
        if ($region !== null) {
            $query['region'] = $region;
        }
        if ($district !== null) {
            $query['district'] = $district;
        }

        return $this->transport->send('GET', '/postoffice/1.0/settlement.offices.codes', query: $query);
    }

    /**
     * Download passport with settlement codes
     */
    public function downloadPassport(): BinaryResponse
    {
        $response = $this->transport->send('GET', '/postoffice/1.0/settlement.offices.codes', expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }
}
