<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Dto\Request\Order\CreateOrderRequest;
use SergeR\RussianPostSDK\Dto\Response\Order\OrderResponse;
use SergeR\RussianPostSDK\Http\HttpTransport;

final class OrderService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * Create order via v1 endpoint
     * @return OrderResponse
     */
    public function create(CreateOrderRequest $req): OrderResponse
    {
        $response = $this->transport->send('PUT', '/1.0/user/backlog', body: [$req->toArray()]);
        return OrderResponse::fromArray(is_array($response) && isset($response[0]) ? $response[0] : $response);
    }

    /**
     * Create order via v2 endpoint
     */
    public function createV2(CreateOrderRequest $req): OrderResponse
    {
        $response = $this->transport->send('PUT', '/2.0/user/backlog', body: [$req->toArray()]);
        return OrderResponse::fromArray(is_array($response) && isset($response[0]) ? $response[0] : $response);
    }

    /**
     * Find orders by query
     * @return array<mixed>
     */
    public function find(string $query): array
    {
        return $this->transport->send('GET', '/1.0/backlog/search', query: ['query' => $query]);
    }

    /**
     * Find order by ID
     */
    public function findById(int $id): OrderResponse
    {
        $response = $this->transport->send('GET', "/1.0/backlog/{$id}");
        return OrderResponse::fromArray($response);
    }

    /**
     * Update order
     */
    public function update(int $id, CreateOrderRequest $req): OrderResponse
    {
        $response = $this->transport->send('PUT', "/1.0/user/backlog/{$id}", body: $req->toArray());
        return OrderResponse::fromArray($response);
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
     * @return array<mixed>
     */
    public function findByGroup(string $groupName): array
    {
        return $this->transport->send('GET', "/1.0/backlog/by-group-name/{$groupName}");
    }
}
