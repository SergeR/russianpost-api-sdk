<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\{RequestFactoryInterface, StreamFactoryInterface};
use Psr\Log\LoggerInterface;
use SergeR\RussianPostSDK\Http\HttpTransport;
use SergeR\RussianPostSDK\Http\Middleware\LoggingMiddleware;
use SergeR\RussianPostSDK\Service\{
    OrderService, BatchService, ArchiveService, DocumentService,
    PostOfficeService, TimeSlotService, ReturnService, UtilityService
};

final class Client
{
    private readonly HttpTransport $transport;
    private ?OrderService $orders = null;
    private ?BatchService $batches = null;
    private ?ArchiveService $archive = null;
    private ?DocumentService $documents = null;
    private ?PostOfficeService $postOffice = null;
    private ?TimeSlotService $timeSlots = null;
    private ?ReturnService $returns = null;
    private ?UtilityService $utility = null;

    public function __construct(
        Config $config,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        ?LoggerInterface $logger = null,
    ) {
        $middleware = $logger !== null ? [new LoggingMiddleware($logger)] : [];
        $this->transport = new HttpTransport($config, $httpClient, $requestFactory, $streamFactory, $middleware);
    }

    public function orders(): OrderService
    {
        return $this->orders ??= new OrderService($this->transport);
    }

    public function batches(): BatchService
    {
        return $this->batches ??= new BatchService($this->transport);
    }

    public function archive(): ArchiveService
    {
        return $this->archive ??= new ArchiveService($this->transport);
    }

    public function documents(): DocumentService
    {
        return $this->documents ??= new DocumentService($this->transport);
    }

    public function postOffice(): PostOfficeService
    {
        return $this->postOffice ??= new PostOfficeService($this->transport);
    }

    public function timeSlots(): TimeSlotService
    {
        return $this->timeSlots ??= new TimeSlotService($this->transport);
    }

    public function returns(): ReturnService
    {
        return $this->returns ??= new ReturnService($this->transport);
    }

    public function utility(): UtilityService
    {
        return $this->utility ??= new UtilityService($this->transport);
    }
}
