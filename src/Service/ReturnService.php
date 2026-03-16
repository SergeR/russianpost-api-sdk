<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Http\HttpTransport;

final class ReturnService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * Create return
     * @param array<string,mixed> $data
     * @return array<mixed>
     */
    public function create(array $data): array
    {
        return $this->transport->send('PUT', '/1.0/returns', body: $data);
    }

    /**
     * Create return without direct shipment
     * @param array<string,mixed> $data
     * @return array<mixed>
     */
    public function createWithoutDirect(array $data): array
    {
        return $this->transport->send('PUT', '/1.0/returns/return-without-direct', body: $data);
    }

    /**
     * Delete separate return
     * @return array<mixed>
     */
    public function deleteSeparate(string $barcode): array
    {
        return $this->transport->send('DELETE', '/1.0/returns/delete-separate-return', query: [
            'barcode' => $barcode,
        ]);
    }

    /**
     * Update return
     * @param array<string,mixed> $data
     * @return array<mixed>
     */
    public function update(string $barcode, array $data): array
    {
        return $this->transport->send('POST', '/1.0/returns/', query: [
            'barcode' => $barcode,
        ], body: $data);
    }
}
