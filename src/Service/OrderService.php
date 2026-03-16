<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Domain\Order;
use SergeR\RussianPostSDK\Http\HttpTransport;

final class OrderService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * Create order via v1 endpoint
     */
    public function create(Order $order): Order
    {
        $response = $this->transport->send('PUT', '/1.0/user/backlog', body: [$order->toArray()]);
        return Order::fromArray(is_array($response) && isset($response[0]) ? $response[0] : $response);
    }

    /**
     * Create order via v2 endpoint
     */
    public function createV2(Order $order): Order
    {
        $response = $this->transport->send('PUT', '/2.0/user/backlog', body: [$order->toArray()]);
        return Order::fromArray(is_array($response) && isset($response[0]) ? $response[0] : $response);
    }

    /**
     * Find orders by query
     * @return array<Order>
     */
    public function find(string $query): array
    {
        $response = $this->transport->send('GET', '/1.0/backlog/search', query: ['query' => $query]);
        return array_map(static fn(array $item) => Order::fromArray($item), $response);
    }

    /**
     * Find order by ID
     */
    public function findById(int $id): Order
    {
        $response = $this->transport->send('GET', "/1.0/backlog/{$id}");
        return Order::fromArray($response);
    }

    /**
     * Update order
     */
    public function update(int $id, Order $order): Order
    {
        $response = $this->transport->send('PUT', "/1.0/user/backlog/{$id}", body: $order->toArray());
        return Order::fromArray($response);
    }

    /**
     * Delete orders by IDs
     * @param array<int> $ids
     * @return array<mixed>
     */
    public function delete(array $ids): array
    {
        return $this->transport->send('DELETE', '/1.0/user/backlog', body: $ids);
    }

    /**
     * Move orders to new
     * @param array<int> $ids
     * @return array<mixed>
     */
    public function moveToNew(array $ids): array
    {
        return $this->transport->send('POST', '/1.0/user/backlog', body: $ids);
    }

    /**
     * Find orders by group name
     * @return array<Order>
     */
    public function findByGroup(string $groupName): array
    {
        $response = $this->transport->send('GET', "/1.0/backlog/by-group-name/{$groupName}");
        return array_map(static fn(array $item) => Order::fromArray($item), $response);
    }
}
