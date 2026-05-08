<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Domain\ShippingPoint;
use SergeR\RussianPostSDK\Http\HttpTransport;

final class UtilityService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * Normalize addresses
     * @param array<mixed> $addresses
     * @return array<mixed>
     */
    public function normalizeAddresses(array $addresses): array
    {
        return $this->transport->send('POST', '/1.0/clean/address', body: $addresses);
    }

    /**
     * Normalize persons
     * @param array<mixed> $persons
     * @return array<mixed>
     */
    public function normalizePersons(array $persons): array
    {
        return $this->transport->send('POST', '/1.0/clean/physical', body: $persons);
    }

    /**
     * Normalize phones
     * @param array<mixed> $phones
     * @return array<mixed>
     */
    public function normalizePhones(array $phones): array
    {
        return $this->transport->send('POST', '/1.0/clean/phone', body: $phones);
    }

    /**
     * Calculate tariff
     * @param array<string,mixed> $data
     * @return array<mixed>
     */
    public function calculateTariff(array $data): array
    {
        return $this->transport->send('POST', '/1.0/tariff', body: $data);
    }

    /**
     * Get user settings
     * @return array<mixed>
     */
    public function getSettings(): array
    {
        return $this->transport->send('GET', '/1.0/settings');
    }

    /**
     * Get user shipping points
     * @return ShippingPoint[]
     */
    public function getShippingPoints(): array
    {
        $data = $this->transport->send('GET', '/1.0/user-shipping-points');

        return array_map(
            static fn(array $item) => ShippingPoint::fromArray($item),
            $data,
        );
    }

    /**
     * Get API rate limit
     * @return array<mixed>
     */
    public function getApiLimit(): array
    {
        return $this->transport->send('GET', '/1.0/settings/limit');
    }
}
