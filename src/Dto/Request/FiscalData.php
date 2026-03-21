<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request;

/**
 * Фискальные данные.
 *
 * @see https://otpravka-api.pochta.ru/specification
 */
final readonly class FiscalData
{
    public function __construct(
        public ?string $customerEmail = null,
        public ?string $customerInn = null,
        public ?string $customerName = null,
        public ?int $customerPhone = null,
        public ?int $paymentAmount = null,
    ) {}

    /**
     * Convert to array for API request.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->customerEmail !== null) {
            $data['customer-email'] = $this->customerEmail;
        }
        if ($this->customerInn !== null) {
            $data['customer-inn'] = $this->customerInn;
        }
        if ($this->customerName !== null) {
            $data['customer-name'] = $this->customerName;
        }
        if ($this->customerPhone !== null) {
            $data['customer-phone'] = $this->customerPhone;
        }
        if ($this->paymentAmount !== null) {
            $data['payment-amount'] = $this->paymentAmount;
        }

        return $data;
    }

    /**
     * Create from API response data.
     *
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            customerEmail: $data['customer-email'] ?? null,
            customerInn: $data['customer-inn'] ?? null,
            customerName: $data['customer-name'] ?? null,
            customerPhone: $data['customer-phone'] ?? null,
            paymentAmount: $data['payment-amount'] ?? null,
        );
    }
}
