<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Domain;

use SergeR\RussianPostSDK\Enums\ReturnAddressType;

final class ShippingPoint
{
    public function __construct(
        public readonly ?string $operatorPostcode = null,
        public readonly ?string $additionalOperatorPostcode = null,
        /** @var string[]|null */
        public readonly ?array $additionalOperatorPostcodes = null,
        /** @var string[]|null */
        public readonly ?array $availableMailTypes = null,
        /** @var array<array<string,string>>|null */
        public readonly ?array $availableProducts = null,
        public readonly ?bool $courierCall = null,
        public readonly ?bool $enabled = null,
        public readonly ?bool $isAgreement = null,
        public readonly ?string $opsAddress = null,
        public readonly ?bool $prePostalPreparation = null,
        public readonly ?ReturnAddressType $returnAddressType = null,
        public readonly ?bool $temporary = null,
        /** @var string[]|null */
        public readonly ?array $userAvailableMailTypes = null,
        /** @var array<array<string,string>>|null */
        public readonly ?array $userAvailableProducts = null,
        /** @var array<string,mixed>|null */
        public readonly ?array $userReturnAddress = null,
        public readonly ?bool $vsdEnabled = null,
    ) {}

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            operatorPostcode: $data['operator-postcode'] ?? null,
            additionalOperatorPostcode: $data['additional-operator-postcode'] ?? null,
            additionalOperatorPostcodes: $data['additional-operator-postcodes'] ?? null,
            availableMailTypes: $data['available-mail-types'] ?? null,
            availableProducts: $data['available-products'] ?? null,
            courierCall: $data['courier-call'] ?? null,
            enabled: $data['enabled'] ?? null,
            isAgreement: $data['is-agreement'] ?? null,
            opsAddress: $data['ops-address'] ?? null,
            prePostalPreparation: $data['pre-postal-preparation'] ?? null,
            returnAddressType: isset($data['return-address-type'])
                ? ReturnAddressType::tryFrom($data['return-address-type'])
                : null,
            temporary: $data['temporary'] ?? null,
            userAvailableMailTypes: $data['user-available-mail-types'] ?? null,
            userAvailableProducts: $data['user-available-products'] ?? null,
            userReturnAddress: $data['user-return-address'] ?? null,
            vsdEnabled: $data['vsd-enabled'] ?? null,
        );
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $result = [];

        if ($this->operatorPostcode !== null) {
            $result['operator-postcode'] = $this->operatorPostcode;
        }
        if ($this->additionalOperatorPostcode !== null) {
            $result['additional-operator-postcode'] = $this->additionalOperatorPostcode;
        }
        if ($this->additionalOperatorPostcodes !== null) {
            $result['additional-operator-postcodes'] = $this->additionalOperatorPostcodes;
        }
        if ($this->availableMailTypes !== null) {
            $result['available-mail-types'] = $this->availableMailTypes;
        }
        if ($this->availableProducts !== null) {
            $result['available-products'] = $this->availableProducts;
        }
        if ($this->courierCall !== null) {
            $result['courier-call'] = $this->courierCall;
        }
        if ($this->enabled !== null) {
            $result['enabled'] = $this->enabled;
        }
        if ($this->isAgreement !== null) {
            $result['is-agreement'] = $this->isAgreement;
        }
        if ($this->opsAddress !== null) {
            $result['ops-address'] = $this->opsAddress;
        }
        if ($this->prePostalPreparation !== null) {
            $result['pre-postal-preparation'] = $this->prePostalPreparation;
        }
        if ($this->returnAddressType !== null) {
            $result['return-address-type'] = $this->returnAddressType->value;
        }
        if ($this->temporary !== null) {
            $result['temporary'] = $this->temporary;
        }
        if ($this->userAvailableMailTypes !== null) {
            $result['user-available-mail-types'] = $this->userAvailableMailTypes;
        }
        if ($this->userAvailableProducts !== null) {
            $result['user-available-products'] = $this->userAvailableProducts;
        }
        if ($this->userReturnAddress !== null) {
            $result['user-return-address'] = $this->userReturnAddress;
        }
        if ($this->vsdEnabled !== null) {
            $result['vsd-enabled'] = $this->vsdEnabled;
        }

        return $result;
    }
}
