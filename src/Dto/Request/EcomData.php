<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request;

/**
 * Данные отправления ЕКОМ.
 *
 * @see https://otpravka-api.pochta.ru/specification
 */
final readonly class EcomData
{
    /**
     * @param list<string>|null $identityMethods
     * @param list<string>|null $services
     */
    public function __construct(
        public ?string $deliveryPointIndex = null,
        public ?array $identityMethods = null,
        public ?array $services = null,
    ) {}

    /**
     * Convert to array for API request.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->deliveryPointIndex !== null) {
            $data['delivery-point-index'] = $this->deliveryPointIndex;
        }
        if ($this->identityMethods !== null) {
            $data['identity-methods'] = $this->identityMethods;
        }
        if ($this->services !== null) {
            $data['services'] = $this->services;
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
            deliveryPointIndex: $data['delivery-point-index'] ?? null,
            identityMethods: $data['identity-methods'] ?? null,
            services: $data['services'] ?? null,
        );
    }
}
