<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request\Order;

use SergeR\RussianPostSDK\Dto\HasKebabCaseSerialization;
use SergeR\RussianPostSDK\Dto\Request\{Address, Dimension, CustomsDeclaration};
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory};

final readonly class CreateOrderRequest
{
    use HasKebabCaseSerialization;

    /**
     * @param ?Address $addressFrom Адрес отправителя
     * @param Address $addressTo Адрес получателя
     */
    public function __construct(
        public string $orderNum,
        public MailType $mailType,
        public MailCategory $mailCategory,
        public int $mass,
        public Address $addressTo,
        public string $recipientName,
        public string $givenName,
        public string $surname,
        public string $region,
        public string $place,
        public string $street,
        public string $house,
        public bool $fragile,
        public int $mailDirect,
        public int $tariffCount,
        public string $addressTypeTo,
        public ?string $comment = null,
        public ?Address $addressFrom = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?int $declaredValue = null,
        public ?int $compulsoryPayment = null,
        public ?int $cashOnDelivery = null,
        public ?Dimension $dimension = null,
        public ?CustomsDeclaration $customsDeclaration = null,
        public ?bool $addToMmo = null,
        public ?bool $courier = null,
        public ?bool $completenessChecking = null,
        public ?string $branchName = null,
        public ?string $areaTo = null,
        public ?string $buildingTo = null,
        public ?string $corpusTo = null,
        public ?string $hotelName = null,
        public ?string $locationTo = null,
        public ?string $roomTo = null,
        public ?string $slashTo = null,
        public ?string $vladenieTo = null,
        public ?string $numAddressTypeTo = null,
    ) {}
}
