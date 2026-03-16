<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Domain\{Batch, Order};
use SergeR\RussianPostSDK\Http\HttpTransport;

final class BatchService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * Create batch from orders
     * @param array<int> $orderIds
     * @return array<mixed>
     */
    public function createFromOrders(array $orderIds, ?string $sendingDate = null, ?int $timezoneOffset = null, ?bool $useOnlineBalance = null): array
    {
        $query = [];
        if ($sendingDate !== null) {
            $query['sending-date'] = $sendingDate;
        }
        if ($timezoneOffset !== null) {
            $query['timezone-offset'] = $timezoneOffset;
        }
        if ($useOnlineBalance !== null) {
            $query['use-online-balance'] = $useOnlineBalance ? 'true' : 'false';
        }

        return $this->transport->send('POST', '/1.0/user/shipment', query: $query, body: $orderIds);
    }

    /**
     * Change batch sending date
     * @return array<mixed>
     */
    public function changeSendingDate(string $name, int $year, int $month, int $day): array
    {
        return $this->transport->send('POST', "/1.0/batch/{$name}/sending/{$year}/{$month}/{$day}");
    }

    /**
     * Add orders to batch
     * @param array<int> $orderIds
     * @return array<mixed>
     */
    public function addOrdersToBatch(string $name, array $orderIds): array
    {
        return $this->transport->send('POST', "/1.0/batch/{$name}/shipment", body: $orderIds);
    }

    /**
     * Find batch by name
     */
    public function find(string $name): Batch
    {
        $response = $this->transport->send('GET', "/1.0/batch/{$name}");
        return Batch::fromArray($response);
    }

    /**
     * Find batches with tracking by query
     * @return array<mixed>
     */
    public function findWithTrack(string $query): array
    {
        return $this->transport->send('GET', '/1.0/shipment/search', query: ['query' => $query]);
    }

    /**
     * Add orders directly to batch
     * @param array<mixed> $orders
     * @return array<mixed>
     */
    public function addOrdersDirectly(string $name, array $orders): array
    {
        return $this->transport->send('PUT', "/1.0/batch/{$name}/shipment", body: $orders);
    }

    /**
     * Remove orders from batch
     * @param array<int> $ids
     * @return array<mixed>
     */
    public function removeOrders(array $ids): array
    {
        return $this->transport->send('DELETE', '/1.0/shipment', body: $ids);
    }

    /**
     * Get orders in batch with pagination
     * @return array<mixed>
     */
    public function getOrders(string $name, int $page = 0, int $size = 20, string $sort = 'asc'): array
    {
        return $this->transport->send('GET', "/1.0/batch/{$name}/shipment", query: [
            'page' => $page,
            'size' => $size,
            'sort' => $sort,
        ]);
    }

    /**
     * Find all batches with filters
     * @param array<string,mixed> $filters
     * @return array<mixed>
     */
    public function findAll(array $filters = []): array
    {
        return $this->transport->send('GET', '/1.0/batch', query: $filters);
    }

    /**
     * Find order in shipment by ID
     */
    public function findOrderById(int $id): Order
    {
        $response = $this->transport->send('GET', "/1.0/shipment/{$id}");
        return Order::fromArray($response);
    }

    /**
     * Find orders by group name
     * @return array<mixed>
     */
    public function findOrdersByGroup(string $groupName): array
    {
        return $this->transport->send('GET', "/1.0/shipment/by-group-name/{$groupName}");
    }

    /**
     * Checkin batch
     * @return array<mixed>
     */
    public function checkin(string $name, ?bool $useOnlineBalance = null): array
    {
        $query = [];
        if ($useOnlineBalance !== null) {
            $query['use-online-balance'] = $useOnlineBalance ? 'true' : 'false';
        }

        return $this->transport->send('GET', "/1.0/batch/{$name}/checkin", query: $query);
    }
}
