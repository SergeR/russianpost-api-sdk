<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Http\HttpTransport;

final class ArchiveService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * List archived batches
     * @return array<mixed>
     */
    public function list(): array
    {
        return $this->transport->send('GET', '/1.0/archive');
    }

    /**
     * Move batches to archive
     * @param array<string> $batchNames
     * @return array<mixed>
     */
    public function moveToArchive(array $batchNames): array
    {
        return $this->transport->send('PUT', '/1.0/archive', body: $batchNames);
    }

    /**
     * Revert batches from archive
     * @param array<string> $batchNames
     * @return array<mixed>
     */
    public function revert(array $batchNames): array
    {
        return $this->transport->send('POST', '/1.0/archive/revert', body: $batchNames);
    }

    /**
     * Search in long-term archive
     * @return array<mixed>
     */
    public function searchLongTerm(string $query): array
    {
        return $this->transport->send('GET', '/1.0/archive/long-term-search', query: ['query' => $query]);
    }
}
