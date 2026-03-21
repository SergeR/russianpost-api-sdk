<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request;

use SergeR\RussianPostSDK\Dto\HasKebabCaseSerialization;

/**
 * Товарное вложение РПО (Goods).
 *
 * @see https://otpravka-api.pochta.ru/specification
 */
final readonly class Goods
{
    /**
     * @param list<GoodsItem> $items
     */
    public function __construct(
        public array $items,
    ) {}

    /**
     * Convert to array for API request.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'items' => array_map(fn (GoodsItem $item) => $item->toArray(), $this->items),
        ];
    }

    /**
     * Create from API response data.
     *
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $items = [];
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = GoodsItem::fromArray($item);
            }
        }

        return new self(items: $items);
    }
}
