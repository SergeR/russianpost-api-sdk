<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Response\Order;

use SergeR\RussianPostSDK\Enums\{MailType, MailCategory};

final readonly class OrderResponse
{
    public function __construct(
        public int $id,
        public ?MailType $mailType = null,
        public ?MailCategory $mailCategory = null,
        public ?string $recipientName = null,
        public ?int $mass = null,
        public ?string $barcode = null,
        public ?string $status = null,
        public ?bool $fragile = null,
        public ?int $declaredValue = null,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            mailType: isset($data['mail-type']) ? MailType::tryFrom($data['mail-type']) : null,
            mailCategory: isset($data['mail-category']) ? MailCategory::tryFrom($data['mail-category']) : null,
            recipientName: $data['recipient-name'] ?? null,
            mass: $data['mass'] ?? null,
            barcode: $data['barcode'] ?? null,
            status: $data['status'] ?? null,
            fragile: $data['fragile'] ?? null,
            declaredValue: $data['declared-value'] ?? null,
        );
    }
}
