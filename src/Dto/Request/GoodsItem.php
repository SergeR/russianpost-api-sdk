<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request;

use SergeR\RussianPostSDK\Dto\HasKebabCaseSerialization;

/**
 * Single item in Goods collection.
 *
 * @see https://otpravka-api.pochta.ru/specification
 */
final readonly class GoodsItem
{
    use HasKebabCaseSerialization;

    public function __construct(
        public string $description,
        public int|float $quantity,
        public ?string $code = null,
        public ?int $countryCode = null,
        public ?string $customsDeclarationNumber = null,
        public ?int $excise = null,
        public ?string $goodId = null,
        public ?string $goodsType = null,
        public ?int $insrValue = null,
        public ?string $itemNumber = null,
        public ?int $lineAttr = null,
        public ?int $payAttr = null,
        public ?int $serviceType = null,
        public ?string $supplierInn = null,
        public ?string $supplierName = null,
        public ?string $supplierPhone = null,
        public ?int $value = null,
        public ?int $vatRate = null,
        public ?int $weight = null,
    ) {}

    /**
     * Convert to array for API request.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [
            'description' => $this->description,
            'quantity' => $this->quantity,
        ];

        if ($this->code !== null) {
            $data['code'] = $this->code;
        }
        if ($this->countryCode !== null) {
            $data['country-code'] = $this->countryCode;
        }
        if ($this->customsDeclarationNumber !== null) {
            $data['customs-declaration-number'] = $this->customsDeclarationNumber;
        }
        if ($this->excise !== null) {
            $data['excise'] = $this->excise;
        }
        if ($this->goodId !== null) {
            $data['good-id'] = $this->goodId;
        }
        if ($this->goodsType !== null) {
            $data['goods-type'] = $this->goodsType;
        }
        if ($this->insrValue !== null) {
            $data['insr-value'] = $this->insrValue;
        }
        if ($this->itemNumber !== null) {
            $data['item-number'] = $this->itemNumber;
        }
        if ($this->lineAttr !== null) {
            $data['lineattr'] = $this->lineAttr;
        }
        if ($this->payAttr !== null) {
            $data['payattr'] = $this->payAttr;
        }
        if ($this->serviceType !== null) {
            $data['service-type'] = $this->serviceType;
        }
        if ($this->supplierInn !== null) {
            $data['supplier-inn'] = $this->supplierInn;
        }
        if ($this->supplierName !== null) {
            $data['supplier-name'] = $this->supplierName;
        }
        if ($this->supplierPhone !== null) {
            $data['supplier-phone'] = $this->supplierPhone;
        }
        if ($this->value !== null) {
            $data['value'] = $this->value;
        }
        if ($this->vatRate !== null) {
            $data['vat-rate'] = $this->vatRate;
        }
        if ($this->weight !== null) {
            $data['weight'] = $this->weight;
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
            description: $data['description'] ?? '',
            quantity: $data['quantity'] ?? 0,
            code: $data['code'] ?? null,
            countryCode: $data['country-code'] ?? null,
            customsDeclarationNumber: $data['customs-declaration-number'] ?? null,
            excise: $data['excise'] ?? null,
            goodId: $data['good-id'] ?? null,
            goodsType: $data['goods-type'] ?? null,
            insrValue: $data['insr-value'] ?? null,
            itemNumber: $data['item-number'] ?? null,
            lineAttr: $data['lineattr'] ?? null,
            payAttr: $data['payattr'] ?? null,
            serviceType: $data['service-type'] ?? null,
            supplierInn: $data['supplier-inn'] ?? null,
            supplierName: $data['supplier-name'] ?? null,
            supplierPhone: $data['supplier-phone'] ?? null,
            value: $data['value'] ?? null,
            vatRate: $data['vat-rate'] ?? null,
            weight: $data['weight'] ?? null,
        );
    }
}
