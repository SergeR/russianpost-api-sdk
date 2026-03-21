<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Domain;

use SergeR\RussianPostSDK\Dto\Request\{Address, Dimension, CustomsDeclaration, Goods, EcomData, FiscalData, PaymentDetails};
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory, AddressType};

/**
 * Доменная модель заказа (посылки) для Russian Post API.
 * Объединяет создание, обновление и представление заказа из API.
 */
final readonly class Order
{
    public function __construct(
        // Идентификаторы и метаданные
        public ?int $id = null,
        public ?string $orderNum = null,
        public ?int $version = null,
        public ?string $barcode = null,
        public ?string $innerNum = null,
        public ?bool $isDeleted = null,

        // Основные параметры отправления
        public ?MailType $mailType = null,
        public ?MailCategory $mailCategory = null,
        public ?string $envelopeType = null,
        public ?int $mass = null,

        // Адрес получателя (обязательные/основные)
        public ?Address $addressTo = null,
        public ?string $addressTypeTo = null,
        public ?string $recipientName = null,
        public ?string $givenName = null,
        public ?string $surname = null,
        public ?string $middleName = null,
        public ?string $region = null,
        public ?string $place = null,
        public ?string $street = null,
        public ?string $house = null,
        public ?int $regionTo = null,
        public ?int $placeTo = null,
        public ?int $indexTo = null,
        public ?string $strIndexTo = null,
        public ?Address $addressFrom = null,
        public ?string $addressTypeFrom = null,
        public ?int $indexFrom = null,
        public ?string $placeFrom = null,
        public ?string $regionFrom = null,
        public ?string $streetFrom = null,
        public ?string $houseFrom = null,

        // Доп. поля адреса получателя
        public ?string $areaTo = null,
        public ?string $buildingTo = null,
        public ?string $corpusTo = null,
        public ?string $hotelTo = null,
        public ?string $locationTo = null,
        public ?string $roomTo = null,
        public ?string $slashTo = null,
        public ?string $vladenieTo = null,
        public ?string $numAddressTypeTo = null,
        public ?string $letterTo = null,
        public ?string $officeTo = null,

        // Доп. поля адреса отправителя
        public ?string $areaFrom = null,
        public ?string $buildingFrom = null,
        public ?string $corpusFrom = null,
        public ?string $hotelFrom = null,
        public ?string $locationFrom = null,
        public ?string $roomFrom = null,
        public ?string $slashFrom = null,
        public ?string $vlandenieFrom = null,
        public ?string $numAddressTypeFrom = null,
        public ?string $letterFrom = null,
        public ?string $officeFrom = null,

        // Контакты
        public ?string $phone = null,
        public ?string $email = null,
        public ?int $telAddress = null,
        public ?int $telAddressFrom = null,

        // Документирование и оценка
        public ?int $declaredValue = null,
        public ?int $insrValue = null,
        public ?int $compulsoryPayment = null,
        public ?int $cashOnDelivery = null,
        public ?int $payment = null,
        public ?bool $fragile = null,
        public ?int $mailDirect = null,
        public ?int $mailRank = null,
        public ?int $tariffCount = null,

        // Размеры и вес
        public ?Dimension $dimension = null,
        public ?string $dimensionType = null,

        // Таможня
        public ?CustomsDeclaration $customsDeclaration = null,
        public ?string $declarationStatus = null,

        // Дополнительные услуги
        public ?bool $addToMmo = null,
        public ?bool $courier = null,
        public ?bool $completenessChecking = null,
        public ?bool $prePostalPreparation = null,
        public ?bool $deliveryWithCod = null,

        // Платежи и сборы
        public ?int $totalRateWoVat = null,
        public ?int $totalVat = null,
        public ?int $massRate = null,
        public ?int $massRateWithVat = null,
        public ?int $massRateWoVat = null,
        public ?int $aviRate = null,
        public ?int $aviRateWithVat = null,
        public ?int $aviRateWoVat = null,
        public ?int $groundRate = null,
        public ?int $groundRateWithVat = null,
        public ?int $groundRateWoVat = null,
        public ?int $insrRate = null,
        public ?int $insrRateWithVat = null,
        public ?int $insrRateWoVat = null,
        public ?int $fragileRateWithVat = null,
        public ?int $fragileRateWoVat = null,
        public ?int $completenessCheckingRateWithVat = null,
        public ?int $completenessCheckingRateWoVat = null,
        public ?int $contentsCheckingRateWithVat = null,
        public ?int $contentsCheckingRateWoVat = null,
        public ?int $functionalityCheckingRateWithVat = null,
        public ?int $functionalityCheckingRateWoVat = null,
        public ?int $withFittingRateWithVat = null,
        public ?int $withFittingRateWoVat = null,
        public ?int $noticeRateWithVat = null,
        public ?int $noticeRateWoVat = null,
        public ?int $inventoryRateWithVat = null,
        public ?int $inventoryRateWoVat = null,
        public ?int $prePotalPreparationRateWithVat = null,
        public ?int $prePotalPreparationRateWoVat = null,
        public ?int $oversizeRateWithVat = null,
        public ?int $oversizeRateWoVat = null,
        public ?int $partialRedempationRateWithVat = null,
        public ?int $partialRedempationRateWoVat = null,
        public ?int $easyReturnRateWithVat = null,
        public ?int $easyReturnRateWoVat = null,
        public ?int $vsdRateWithVat = null,
        public ?int $vsdRateWoVat = null,
        public ?int $smsNoticeRecipientRateWithVat = null,
        public ?int $smsNoticeRecipientRateWoVat = null,

        // Метаданные доставки
        public ?string $status = null,
        public ?string $transportType = null,
        public ?string $transportMode = null,
        public ?string $paymentMethod = null,
        public ?string $noticePaymentMethod = null,
        public ?string $comment = null,
        public ?string $groupName = null,
        public ?string $postofficeCode = null,
        public ?string $branchName = null,
        public ?bool $inMmo = null,
        public ?bool $tender = null,

        // Доп. поля
        public ?string $senderName = null,
        public ?string $senderComment = null,
        public ?string $rawAddress = null,
        public ?bool $addressChanged = null,
        public ?string $linkedBarcode = null,
        public ?int $shelfLifeDays = null,
        public ?int $bkHash = null,
        public ?int $smsNoticeRecipient = null,
        public ?string $declarationNumber = null,

        // Доп. услуги и отметки
        public ?bool $deliveryToDoor = null,
        public ?bool $easyReturn = null,
        public ?bool $farma = null,
        public ?bool $inventory = null,
        public ?bool $manualAddressInput = null,
        public ?bool $noReturn = null,
        public ?bool $vsd = null,
        public ?bool $withDiscount = null,
        public ?bool $withDocuments = null,
        public ?bool $withElectronicNotice = null,
        public ?bool $withGoods = null,
        public ?bool $withOrderOfNotice = null,
        public ?bool $withPackaging = null,
        public ?bool $withSimpleNotice = null,
        public ?bool $withUzeuVNotice = null,
        public ?bool $woMailRank = null,

        // Платежные параметры и временной интервал
        public ?int $prepaidAmount = null,
        public ?int $timeSlotId = null,
        public ?string $sellerId = null,

        // Тариф-информация
        public ?string $tariffFullRate = null,
        public ?string $tariffFullRateVat = null,
        public ?string $tariffFullRateWithVat = null,
        public ?string $tariffDiscountRate = null,
        public ?string $tariffDiscountRateVat = null,
        public ?string $tariffDiscountRateWithVat = null,

        // Сложные DTO объекты
        public ?Goods $goods = null,
        public ?EcomData $ecomData = null,
        public ?FiscalData $fiscalData = null,
        public ?PaymentDetails $paymentDetails = null,

        // Коллекции
        /** @var list<string> */
        public ?array $postmarks = null,
    ) {}

    /**
     * Преобразует Order в массив для отправки в API.
     * Null значения исключаются.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->orderNum !== null) {
            $data['order-num'] = $this->orderNum;
        }
        if ($this->mailType !== null) {
            $data['mail-type'] = $this->mailType->value;
        }
        if ($this->mailCategory !== null) {
            $data['mail-category'] = $this->mailCategory->value;
        }
        if ($this->mass !== null) {
            $data['mass'] = $this->mass;
        }
        if ($this->addressTo !== null) {
            $data['address-to'] = $this->addressTo->toArray();
        }
        if ($this->recipientName !== null) {
            $data['recipient-name'] = $this->recipientName;
        }
        if ($this->givenName !== null) {
            $data['given-name'] = $this->givenName;
        }
        if ($this->surname !== null) {
            $data['surname'] = $this->surname;
        }
        if ($this->middleName !== null) {
            $data['middle-name'] = $this->middleName;
        }
        if ($this->region !== null) {
            $data['region'] = $this->region;
        }
        if ($this->place !== null) {
            $data['place'] = $this->place;
        }
        if ($this->street !== null) {
            $data['street'] = $this->street;
        }
        if ($this->house !== null) {
            $data['house'] = $this->house;
        }
        if ($this->phone !== null) {
            $data['phone'] = $this->phone;
        }
        if ($this->email !== null) {
            $data['email'] = $this->email;
        }
        if ($this->fragile !== null) {
            $data['fragile'] = $this->fragile;
        }
        if ($this->mailDirect !== null) {
            $data['mail-direct'] = $this->mailDirect;
        }
        if ($this->tariffCount !== null) {
            $data['tariff-count'] = $this->tariffCount;
        }
        if ($this->addressTypeTo !== null) {
            $data['address-type-to'] = $this->addressTypeTo;
        }
        if ($this->comment !== null) {
            $data['comment'] = $this->comment;
        }
        if ($this->addressFrom !== null) {
            $data['address-from'] = $this->addressFrom->toArray();
        }
        if ($this->declaredValue !== null) {
            $data['declared-value'] = $this->declaredValue;
        }
        if ($this->compulsoryPayment !== null) {
            $data['compulsory-payment'] = $this->compulsoryPayment;
        }
        if ($this->cashOnDelivery !== null) {
            $data['cash-on-delivery'] = $this->cashOnDelivery;
        }
        if ($this->dimension !== null) {
            $data['dimension'] = $this->dimension->toArray();
        }
        if ($this->customsDeclaration !== null) {
            $data['customs-declaration'] = $this->customsDeclaration->toArray();
        }
        if ($this->addToMmo !== null) {
            $data['add-to-mmo'] = $this->addToMmo;
        }
        if ($this->courier !== null) {
            $data['courier'] = $this->courier;
        }
        if ($this->completenessChecking !== null) {
            $data['completeness-checking'] = $this->completenessChecking;
        }
        if ($this->branchName !== null) {
            $data['branch-name'] = $this->branchName;
        }
        if ($this->areaTo !== null) {
            $data['area-to'] = $this->areaTo;
        }
        if ($this->buildingTo !== null) {
            $data['building-to'] = $this->buildingTo;
        }
        if ($this->corpusTo !== null) {
            $data['corpus-to'] = $this->corpusTo;
        }
        if ($this->hotelTo !== null) {
            $data['hotel-to'] = $this->hotelTo;
        }
        if ($this->locationTo !== null) {
            $data['location-to'] = $this->locationTo;
        }
        if ($this->roomTo !== null) {
            $data['room-to'] = $this->roomTo;
        }
        if ($this->slashTo !== null) {
            $data['slash-to'] = $this->slashTo;
        }
        if ($this->vladenieTo !== null) {
            $data['vladenie-to'] = $this->vladenieTo;
        }
        if ($this->numAddressTypeTo !== null) {
            $data['num-address-type-to'] = $this->numAddressTypeTo;
        }
        if ($this->deliveryToDoor !== null) {
            $data['delivery-to-door'] = $this->deliveryToDoor;
        }
        if ($this->easyReturn !== null) {
            $data['easy-return'] = $this->easyReturn;
        }
        if ($this->farma !== null) {
            $data['farma'] = $this->farma;
        }
        if ($this->inventory !== null) {
            $data['inventory'] = $this->inventory;
        }
        if ($this->manualAddressInput !== null) {
            $data['manual-address-input'] = $this->manualAddressInput;
        }
        if ($this->noReturn !== null) {
            $data['no-return'] = $this->noReturn;
        }
        if ($this->vsd !== null) {
            $data['vsd'] = $this->vsd;
        }
        if ($this->withDiscount !== null) {
            $data['with-discount'] = $this->withDiscount;
        }
        if ($this->withDocuments !== null) {
            $data['with-documents'] = $this->withDocuments;
        }
        if ($this->withElectronicNotice !== null) {
            $data['with-electronic-notice'] = $this->withElectronicNotice;
        }
        if ($this->withGoods !== null) {
            $data['with-goods'] = $this->withGoods;
        }
        if ($this->withOrderOfNotice !== null) {
            $data['with-order-of-notice'] = $this->withOrderOfNotice;
        }
        if ($this->withPackaging !== null) {
            $data['with-packaging'] = $this->withPackaging;
        }
        if ($this->withSimpleNotice !== null) {
            $data['with-simple-notice'] = $this->withSimpleNotice;
        }
        if ($this->withUzeuVNotice !== null) {
            $data['with-uzeuv-notice'] = $this->withUzeuVNotice;
        }
        if ($this->woMailRank !== null) {
            $data['wo-mail-rank'] = $this->woMailRank;
        }
        if ($this->prepaidAmount !== null) {
            $data['prepaid-amount'] = $this->prepaidAmount;
        }
        if ($this->timeSlotId !== null) {
            $data['time-slot-id'] = $this->timeSlotId;
        }
        if ($this->sellerId !== null) {
            $data['seller-id'] = $this->sellerId;
        }
        if ($this->tariffFullRate !== null) {
            $data['tariff-full-rate'] = $this->tariffFullRate;
        }
        if ($this->tariffFullRateVat !== null) {
            $data['tariff-full-rate-vat'] = $this->tariffFullRateVat;
        }
        if ($this->tariffFullRateWithVat !== null) {
            $data['tariff-full-rate-with-vat'] = $this->tariffFullRateWithVat;
        }
        if ($this->tariffDiscountRate !== null) {
            $data['tariff-discount-rate'] = $this->tariffDiscountRate;
        }
        if ($this->tariffDiscountRateVat !== null) {
            $data['tariff-discount-rate-vat'] = $this->tariffDiscountRateVat;
        }
        if ($this->tariffDiscountRateWithVat !== null) {
            $data['tariff-discount-rate-with-vat'] = $this->tariffDiscountRateWithVat;
        }
        if ($this->goods !== null) {
            $data['goods'] = $this->goods->toArray();
        }
        if ($this->ecomData !== null) {
            $data['ecom-data'] = $this->ecomData->toArray();
        }
        if ($this->fiscalData !== null) {
            $data['fiscal-data'] = $this->fiscalData->toArray();
        }
        if ($this->paymentDetails !== null) {
            $data['payment-details'] = $this->paymentDetails->toArray();
        }

        return $data;
    }

    /**
     * Создаёт Order из массива API.
     *
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            orderNum: $data['order-num'] ?? null,
            version: $data['version'] ?? null,
            barcode: $data['barcode'] ?? null,
            innerNum: $data['inner-num'] ?? null,
            isDeleted: $data['is-deleted'] ?? null,
            mailType: isset($data['mail-type']) ? MailType::tryFrom($data['mail-type']) : null,
            mailCategory: isset($data['mail-category']) ? MailCategory::tryFrom($data['mail-category']) : null,
            envelopeType: $data['envelope-type'] ?? null,
            mass: $data['mass'] ?? null,
            addressTo: isset($data['address-to']) ? Address::fromArray($data['address-to']) : null,
            addressTypeTo: $data['address-type-to'] ?? null,
            recipientName: $data['recipient-name'] ?? null,
            givenName: $data['given-name'] ?? null,
            surname: $data['surname'] ?? null,
            middleName: $data['middle-name'] ?? null,
            region: $data['region'] ?? null,
            place: $data['place'] ?? null,
            street: $data['street'] ?? null,
            house: $data['house'] ?? null,
            regionTo: $data['region-to'] ?? null,
            placeTo: $data['place-to'] ?? null,
            indexTo: $data['index-to'] ?? null,
            strIndexTo: $data['str-index-to'] ?? null,
            addressFrom: isset($data['address-from']) ? Address::fromArray($data['address-from']) : null,
            addressTypeFrom: $data['address-type-from'] ?? null,
            indexFrom: $data['index-from'] ?? null,
            placeFrom: $data['place-from'] ?? null,
            regionFrom: $data['region-from'] ?? null,
            streetFrom: $data['street-from'] ?? null,
            houseFrom: $data['house-from'] ?? null,
            areaTo: $data['area-to'] ?? null,
            buildingTo: $data['building-to'] ?? null,
            corpusTo: $data['corpus-to'] ?? null,
            hotelTo: $data['hotel-to'] ?? null,
            locationTo: $data['location-to'] ?? null,
            roomTo: $data['room-to'] ?? null,
            slashTo: $data['slash-to'] ?? null,
            vladenieTo: $data['vladenie-to'] ?? null,
            numAddressTypeTo: $data['num-address-type-to'] ?? null,
            letterTo: $data['letter-to'] ?? null,
            officeTo: $data['office-to'] ?? null,
            areaFrom: $data['area-from'] ?? null,
            buildingFrom: $data['building-from'] ?? null,
            corpusFrom: $data['corpus-from'] ?? null,
            hotelFrom: $data['hotel-from'] ?? null,
            locationFrom: $data['location-from'] ?? null,
            roomFrom: $data['room-from'] ?? null,
            slashFrom: $data['slash-from'] ?? null,
            vlandenieFrom: $data['vladenie-from'] ?? null,
            numAddressTypeFrom: $data['num-address-type-from'] ?? null,
            letterFrom: $data['letter-from'] ?? null,
            officeFrom: $data['office-from'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            telAddress: $data['tel-address'] ?? null,
            telAddressFrom: $data['tel-address-from'] ?? null,
            declaredValue: $data['declared-value'] ?? null,
            insrValue: $data['insr-value'] ?? null,
            compulsoryPayment: $data['compulsory-payment'] ?? null,
            cashOnDelivery: $data['cash-on-delivery'] ?? null,
            payment: $data['payment'] ?? null,
            fragile: $data['fragile'] ?? null,
            mailDirect: $data['mail-direct'] ?? null,
            mailRank: isset($data['mail-rank']) ? (int) $data['mail-rank'] : null,
            tariffCount: $data['tariff-count'] ?? null,
            dimension: isset($data['dimension']) ? Dimension::fromArray($data['dimension']) : null,
            dimensionType: $data['dimension-type'] ?? null,
            customsDeclaration: isset($data['customs-declaration']) ? CustomsDeclaration::fromArray($data['customs-declaration']) : null,
            declarationStatus: $data['declaration-status'] ?? null,
            addToMmo: $data['add-to-mmo'] ?? null,
            courier: $data['courier'] ?? null,
            completenessChecking: $data['completeness-checking'] ?? null,
            prePostalPreparation: $data['pre-postal-preparation'] ?? null,
            deliveryWithCod: $data['delivery-with-cod'] ?? null,
            totalRateWoVat: $data['total-rate-wo-vat'] ?? null,
            totalVat: $data['total-vat'] ?? null,
            massRate: $data['mass-rate'] ?? null,
            massRateWithVat: $data['mass-rate-with-vat'] ?? null,
            massRateWoVat: $data['mass-rate-wo-vat'] ?? null,
            aviRate: $data['avia-rate'] ?? null,
            aviRateWithVat: $data['avia-rate-with-vat'] ?? null,
            aviRateWoVat: $data['avia-rate-wo-vat'] ?? null,
            groundRate: $data['ground-rate'] ?? null,
            groundRateWithVat: $data['ground-rate-with-vat'] ?? null,
            groundRateWoVat: $data['ground-rate-wo-vat'] ?? null,
            insrRate: $data['insr-rate'] ?? null,
            insrRateWithVat: $data['insr-rate-with-vat'] ?? null,
            insrRateWoVat: $data['insr-rate-wo-vat'] ?? null,
            fragileRateWithVat: $data['fragile-rate-with-vat'] ?? null,
            fragileRateWoVat: $data['fragile-rate-wo-vat'] ?? null,
            completenessCheckingRateWithVat: $data['completeness-checking-rate-with-vat'] ?? null,
            completenessCheckingRateWoVat: $data['completeness-checking-rate-wo-vat'] ?? null,
            contentsCheckingRateWithVat: $data['contents-checking-rate-with-vat'] ?? null,
            contentsCheckingRateWoVat: $data['contents-checking-rate-wo-vat'] ?? null,
            functionalityCheckingRateWithVat: $data['functionality-checking-rate-with-vat'] ?? null,
            functionalityCheckingRateWoVat: $data['functionality-checking-rate-wo-vat'] ?? null,
            withFittingRateWithVat: $data['with-fitting-rate-with-vat'] ?? null,
            withFittingRateWoVat: $data['with-fitting-rate-wo-vat'] ?? null,
            noticeRateWithVat: $data['notice-rate-with-vat'] ?? null,
            noticeRateWoVat: $data['notice-rate-wo-vat'] ?? null,
            inventoryRateWithVat: $data['inventory-rate-with-vat'] ?? null,
            inventoryRateWoVat: $data['inventory-rate-wo-vat'] ?? null,
            prePotalPreparationRateWithVat: $data['pre-postal-preparation-rate-with-vat'] ?? null,
            prePotalPreparationRateWoVat: $data['pre-postal-preparation-rate-wo-vat'] ?? null,
            oversizeRateWithVat: $data['oversize-rate-with-vat'] ?? null,
            oversizeRateWoVat: $data['oversize-rate-wo-vat'] ?? null,
            partialRedempationRateWithVat: $data['partial-redemption-rate-with-vat'] ?? null,
            partialRedempationRateWoVat: $data['partial-redemption-rate-wo-vat'] ?? null,
            easyReturnRateWithVat: $data['easy-return-rate-with-vat'] ?? null,
            easyReturnRateWoVat: $data['easy-return-rate-wo-vat'] ?? null,
            vsdRateWithVat: $data['vsd-rate-with-vat'] ?? null,
            vsdRateWoVat: $data['vsd-rate-wo-vat'] ?? null,
            smsNoticeRecipientRateWithVat: $data['sms-notice-recipient-rate-with-vat'] ?? null,
            smsNoticeRecipientRateWoVat: $data['sms-notice-recipient-rate-wo-vat'] ?? null,
            status: $data['status'] ?? null,
            transportType: $data['transport-type'] ?? null,
            transportMode: $data['transport-mode'] ?? null,
            paymentMethod: $data['payment-method'] ?? null,
            noticePaymentMethod: $data['notice-payment-method'] ?? null,
            comment: $data['comment'] ?? null,
            groupName: $data['group-name'] ?? null,
            postofficeCode: $data['postoffice-code'] ?? null,
            branchName: $data['branch-name'] ?? null,
            inMmo: $data['in-mmo'] ?? null,
            tender: $data['tender'] ?? null,
            senderName: $data['sender-name'] ?? null,
            senderComment: $data['sender-comment'] ?? null,
            rawAddress: $data['raw-address'] ?? null,
            addressChanged: $data['address-changed'] ?? null,
            linkedBarcode: $data['linked-barcode'] ?? null,
            shelfLifeDays: $data['shelf-life-days'] ?? null,
            bkHash: $data['bk-hash'] ?? null,
            smsNoticeRecipient: $data['sms-notice-recipient'] ?? null,
            declarationNumber: $data['declaration-number'] ?? null,
            deliveryToDoor: $data['delivery-to-door'] ?? null,
            easyReturn: $data['easy-return'] ?? null,
            farma: $data['farma'] ?? null,
            inventory: $data['inventory'] ?? null,
            manualAddressInput: $data['manual-address-input'] ?? null,
            noReturn: $data['no-return'] ?? null,
            vsd: $data['vsd'] ?? null,
            withDiscount: $data['with-discount'] ?? null,
            withDocuments: $data['with-documents'] ?? null,
            withElectronicNotice: $data['with-electronic-notice'] ?? null,
            withGoods: $data['with-goods'] ?? null,
            withOrderOfNotice: $data['with-order-of-notice'] ?? null,
            withPackaging: $data['with-packaging'] ?? null,
            withSimpleNotice: $data['with-simple-notice'] ?? null,
            withUzeuVNotice: $data['with-uzeuv-notice'] ?? null,
            woMailRank: $data['wo-mail-rank'] ?? null,
            prepaidAmount: $data['prepaid-amount'] ?? null,
            timeSlotId: $data['time-slot-id'] ?? null,
            sellerId: $data['seller-id'] ?? null,
            tariffFullRate: $data['tariff-full-rate'] ?? null,
            tariffFullRateVat: $data['tariff-full-rate-vat'] ?? null,
            tariffFullRateWithVat: $data['tariff-full-rate-with-vat'] ?? null,
            tariffDiscountRate: $data['tariff-discount-rate'] ?? null,
            tariffDiscountRateVat: $data['tariff-discount-rate-vat'] ?? null,
            tariffDiscountRateWithVat: $data['tariff-discount-rate-with-vat'] ?? null,
            goods: isset($data['goods']) ? Goods::fromArray($data['goods']) : null,
            ecomData: isset($data['ecom-data']) ? EcomData::fromArray($data['ecom-data']) : null,
            fiscalData: isset($data['fiscal-data']) ? FiscalData::fromArray($data['fiscal-data']) : null,
            paymentDetails: isset($data['payment-details']) ? PaymentDetails::fromArray($data['payment-details']) : null,
            postmarks: $data['postmarks'] ?? null,
        );
    }
}
