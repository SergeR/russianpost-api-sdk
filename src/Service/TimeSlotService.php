<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Http\HttpTransport;

final class TimeSlotService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * Get available timeslots
     * @param array<string,mixed> $params
     * @return array<mixed>
     */
    public function getAvailable(array $params): array
    {
        return $this->transport->send('GET', '/external/v1/timeslots-by-postindex', query: $params);
    }

    /**
     * Book a timeslot
     * @param array<string,mixed> $data
     * @return array<mixed>
     */
    public function book(array $data): array
    {
        return $this->transport->send('POST', '/external/v1/booking-by-postindex', body: $data);
    }

    /**
     * Confirm timeslot booking
     * @return array<mixed>
     */
    public function confirmBooking(string $uuid, string $barcode): array
    {
        return $this->transport->send('PUT', '/external/v1/booking-by-postindex', query: [
            'uuid' => $uuid,
            'barcode' => $barcode,
        ]);
    }

    /**
     * Get timeslots available for rebooking
     * @return array<mixed>
     */
    public function getForRebooking(string $uuidOrBarcode, string $plannedDate, ?string $contractNumber = null): array
    {
        $query = [
            'uuid' => $uuidOrBarcode,
            'planned-date' => $plannedDate,
        ];
        if ($contractNumber !== null) {
            $query['contract-number'] = $contractNumber;
        }

        return $this->transport->send('GET', '/external/v1/timeslots-for-rebooking', query: $query);
    }

    /**
     * Rebook timeslot
     * @param array<string,mixed> $data
     * @return array<mixed>
     */
    public function rebook(array $data): array
    {
        return $this->transport->send('POST', '/external/v1/booking-by-postindex', body: $data);
    }

    /**
     * Cancel timeslot booking
     */
    public function cancel(string $uuidOrBarcode): void
    {
        $this->transport->send('DELETE', "/external/v1/booking-by-postindex/{$uuidOrBarcode}");
    }
}
