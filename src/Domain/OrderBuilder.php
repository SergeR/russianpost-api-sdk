<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Domain;

use SergeR\RussianPostSDK\Dto\Request\{Address, Dimension, CustomsDeclaration};
use SergeR\RussianPostSDK\Enums\{MailType, MailCategory};

/**
 * Fluent builder for constructing Order domain models.
 * Makes it easy to build Orders with many optional fields.
 *
 * Example:
 *   $order = OrderBuilder::create()
 *       ->orderNum('ORDER-001')
 *       ->mailType(MailType::ONLINE_PARCEL)
 *       ->mailCategory(MailCategory::SIMPLE)
 *       ->mass(1000)
 *       ->recipient('John', 'Doe', 'john@example.com')
 *       ->addressTo(new Address(...))
 *       ->build();
 */
final class OrderBuilder
{
    private ?int $id = null;
    private ?string $orderNum = null;
    private ?int $version = null;
    private ?string $barcode = null;
    private ?string $innerNum = null;
    private ?bool $isDeleted = null;
    private ?MailType $mailType = null;
    private ?MailCategory $mailCategory = null;
    private ?string $envelopeType = null;
    private ?int $mass = null;
    private ?Address $addressTo = null;
    private ?string $addressTypeTo = null;
    private ?string $recipientName = null;
    private ?string $givenName = null;
    private ?string $surname = null;
    private ?string $middleName = null;
    private ?string $region = null;
    private ?string $place = null;
    private ?string $street = null;
    private ?string $house = null;
    private ?int $regionTo = null;
    private ?int $placeTo = null;
    private ?int $indexTo = null;
    private ?string $strIndexTo = null;
    private ?Address $addressFrom = null;
    private ?string $addressTypeFrom = null;
    private ?int $indexFrom = null;
    private ?string $placeFrom = null;
    private ?string $regionFrom = null;
    private ?string $streetFrom = null;
    private ?string $houseFrom = null;
    private ?string $areaTo = null;
    private ?string $buildingTo = null;
    private ?string $corpusTo = null;
    private ?string $hotelTo = null;
    private ?string $locationTo = null;
    private ?string $roomTo = null;
    private ?string $slashTo = null;
    private ?string $vladenieTo = null;
    private ?string $numAddressTypeTo = null;
    private ?string $letterTo = null;
    private ?string $officeTo = null;
    private ?string $areaFrom = null;
    private ?string $buildingFrom = null;
    private ?string $corpusFrom = null;
    private ?string $hotelFrom = null;
    private ?string $locationFrom = null;
    private ?string $roomFrom = null;
    private ?string $slashFrom = null;
    private ?string $vlandenieFrom = null;
    private ?string $numAddressTypeFrom = null;
    private ?string $letterFrom = null;
    private ?string $officeFrom = null;
    private ?string $phone = null;
    private ?string $email = null;
    private ?int $telAddress = null;
    private ?int $telAddressFrom = null;
    private ?int $declaredValue = null;
    private ?int $insrValue = null;
    private ?int $compulsoryPayment = null;
    private ?int $cashOnDelivery = null;
    private ?int $payment = null;
    private ?bool $fragile = null;
    private ?int $mailDirect = null;
    private ?int $mailRank = null;
    private ?int $tariffCount = null;
    private ?Dimension $dimension = null;
    private ?string $dimensionType = null;
    private ?CustomsDeclaration $customsDeclaration = null;
    private ?string $declarationStatus = null;
    private ?bool $addToMmo = null;
    private ?bool $courier = null;
    private ?bool $completenessChecking = null;
    private ?bool $prePostalPreparation = null;
    private ?bool $deliveryWithCod = null;
    private ?int $totalRateWoVat = null;
    private ?int $totalVat = null;
    private ?int $massRate = null;
    private ?int $massRateWithVat = null;
    private ?int $massRateWoVat = null;
    private ?int $aviRate = null;
    private ?int $aviRateWithVat = null;
    private ?int $aviRateWoVat = null;
    private ?int $groundRate = null;
    private ?int $groundRateWithVat = null;
    private ?int $groundRateWoVat = null;
    private ?int $insrRate = null;
    private ?int $insrRateWithVat = null;
    private ?int $insrRateWoVat = null;
    private ?int $fragileRateWithVat = null;
    private ?int $fragileRateWoVat = null;
    private ?int $completenessCheckingRateWithVat = null;
    private ?int $completenessCheckingRateWoVat = null;
    private ?int $contentsCheckingRateWithVat = null;
    private ?int $contentsCheckingRateWoVat = null;
    private ?int $functionalityCheckingRateWithVat = null;
    private ?int $functionalityCheckingRateWoVat = null;
    private ?int $withFittingRateWithVat = null;
    private ?int $withFittingRateWoVat = null;
    private ?int $noticeRateWithVat = null;
    private ?int $noticeRateWoVat = null;
    private ?int $inventoryRateWithVat = null;
    private ?int $inventoryRateWoVat = null;
    private ?int $prePotalPreparationRateWithVat = null;
    private ?int $prePotalPreparationRateWoVat = null;
    private ?int $oversizeRateWithVat = null;
    private ?int $oversizeRateWoVat = null;
    private ?int $partialRedempationRateWithVat = null;
    private ?int $partialRedempationRateWoVat = null;
    private ?int $easyReturnRateWithVat = null;
    private ?int $easyReturnRateWoVat = null;
    private ?int $vsdRateWithVat = null;
    private ?int $vsdRateWoVat = null;
    private ?int $smsNoticeRecipientRateWithVat = null;
    private ?int $smsNoticeRecipientRateWoVat = null;
    private ?string $status = null;
    private ?string $transportType = null;
    private ?string $transportMode = null;
    private ?string $paymentMethod = null;
    private ?string $noticePaymentMethod = null;
    private ?string $comment = null;
    private ?string $groupName = null;
    private ?string $postofficeCode = null;
    private ?string $branchName = null;
    private ?bool $inMmo = null;
    private ?bool $tender = null;
    private ?string $senderName = null;
    private ?string $senderComment = null;
    private ?string $rawAddress = null;
    private ?bool $addressChanged = null;
    private ?string $linkedBarcode = null;
    private ?int $shelfLifeDays = null;
    private ?int $bkHash = null;
    private ?int $smsNoticeRecipient = null;
    /** @var list<string>|null */
    private ?array $postmarks = null;

    private function __construct() {}

    /**
     * Validate required fields for Order creation.
     *
     * @throws \InvalidArgumentException
     */
    private function validate(): void
    {
        $errors = [];

        if ($this->orderNum === null) {
            $errors[] = 'orderNum is required';
        }
        if ($this->mailType === null) {
            $errors[] = 'mailType is required';
        }
        if ($this->mailCategory === null) {
            $errors[] = 'mailCategory is required';
        }
        if ($this->mass === null) {
            $errors[] = 'mass is required (in grams)';
        }
        if ($this->recipientName === null && ($this->surname === null || $this->givenName === null)) {
            $errors[] = 'recipientName or both surname and givenName are required';
        }
        if ($this->addressTo === null) {
            $errors[] = 'addressTo is required';
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(
                'Cannot build Order: ' . implode(', ', $errors)
            );
        }
    }

    public static function create(): self
    {
        return new self();
    }

    public function id(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function orderNum(string $orderNum): self
    {
        $this->orderNum = $orderNum;
        return $this;
    }

    public function mailType(MailType $mailType): self
    {
        $this->mailType = $mailType;
        return $this;
    }

    public function mailCategory(MailCategory $mailCategory): self
    {
        $this->mailCategory = $mailCategory;
        return $this;
    }

    public function mass(int $mass): self
    {
        $this->mass = $mass;
        return $this;
    }

    public function addressTo(Address $addressTo): self
    {
        $this->addressTo = $addressTo;
        return $this;
    }

    public function recipient(string $surname, string $givenName, ?string $middleName = null): self
    {
        $this->surname = $surname;
        $this->givenName = $givenName;
        $this->middleName = $middleName;
        $this->recipientName = trim("{$givenName} {$surname}" . ($middleName ? " {$middleName}" : ''));
        return $this;
    }

    public function recipientName(string $name): self
    {
        $this->recipientName = $name;
        return $this;
    }

    public function givenName(string $givenName): self
    {
        $this->givenName = $givenName;
        return $this;
    }

    public function surname(string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function middleName(string $middleName): self
    {
        $this->middleName = $middleName;
        return $this;
    }

    public function region(string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function place(string $place): self
    {
        $this->place = $place;
        return $this;
    }

    public function street(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function house(string $house): self
    {
        $this->house = $house;
        return $this;
    }

    public function addressTypeTo(string $addressTypeTo): self
    {
        $this->addressTypeTo = $addressTypeTo;
        return $this;
    }

    public function fragile(bool $fragile): self
    {
        $this->fragile = $fragile;
        return $this;
    }

    public function mailDirect(int $mailDirect): self
    {
        $this->mailDirect = $mailDirect;
        return $this;
    }

    public function tariffCount(int $tariffCount): self
    {
        $this->tariffCount = $tariffCount;
        return $this;
    }

    public function phone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function declaredValue(int $declaredValue): self
    {
        $this->declaredValue = $declaredValue;
        return $this;
    }

    public function compulsoryPayment(int $amount): self
    {
        $this->compulsoryPayment = $amount;
        return $this;
    }

    public function cashOnDelivery(int $amount): self
    {
        $this->cashOnDelivery = $amount;
        return $this;
    }

    public function dimension(Dimension $dimension): self
    {
        $this->dimension = $dimension;
        return $this;
    }

    public function customsDeclaration(CustomsDeclaration $customsDeclaration): self
    {
        $this->customsDeclaration = $customsDeclaration;
        return $this;
    }

    public function comment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function courier(bool $courier): self
    {
        $this->courier = $courier;
        return $this;
    }

    public function groupName(string $groupName): self
    {
        $this->groupName = $groupName;
        return $this;
    }

    public function barcode(string $barcode): self
    {
        $this->barcode = $barcode;
        return $this;
    }

    public function status(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function transportType(string $transportType): self
    {
        $this->transportType = $transportType;
        return $this;
    }

    public function paymentMethod(string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * @param list<string> $postmarks
     */
    public function postmarks(array $postmarks): self
    {
        $this->postmarks = $postmarks;
        return $this;
    }

    /**
     * Build and validate the Order.
     *
     * @throws \InvalidArgumentException if required fields are missing
     */
    public function build(): Order
    {
        $this->validate();

        return new Order(
            id: $this->id,
            orderNum: $this->orderNum,
            version: $this->version,
            barcode: $this->barcode,
            innerNum: $this->innerNum,
            isDeleted: $this->isDeleted,
            mailType: $this->mailType,
            mailCategory: $this->mailCategory,
            envelopeType: $this->envelopeType,
            mass: $this->mass,
            addressTo: $this->addressTo,
            addressTypeTo: $this->addressTypeTo,
            recipientName: $this->recipientName,
            givenName: $this->givenName,
            surname: $this->surname,
            middleName: $this->middleName,
            region: $this->region,
            place: $this->place,
            street: $this->street,
            house: $this->house,
            regionTo: $this->regionTo,
            placeTo: $this->placeTo,
            indexTo: $this->indexTo,
            strIndexTo: $this->strIndexTo,
            addressFrom: $this->addressFrom,
            addressTypeFrom: $this->addressTypeFrom,
            indexFrom: $this->indexFrom,
            placeFrom: $this->placeFrom,
            regionFrom: $this->regionFrom,
            streetFrom: $this->streetFrom,
            houseFrom: $this->houseFrom,
            areaTo: $this->areaTo,
            buildingTo: $this->buildingTo,
            corpusTo: $this->corpusTo,
            hotelTo: $this->hotelTo,
            locationTo: $this->locationTo,
            roomTo: $this->roomTo,
            slashTo: $this->slashTo,
            vladenieTo: $this->vladenieTo,
            numAddressTypeTo: $this->numAddressTypeTo,
            letterTo: $this->letterTo,
            officeTo: $this->officeTo,
            areaFrom: $this->areaFrom,
            buildingFrom: $this->buildingFrom,
            corpusFrom: $this->corpusFrom,
            hotelFrom: $this->hotelFrom,
            locationFrom: $this->locationFrom,
            roomFrom: $this->roomFrom,
            slashFrom: $this->slashFrom,
            vlandenieFrom: $this->vlandenieFrom,
            numAddressTypeFrom: $this->numAddressTypeFrom,
            letterFrom: $this->letterFrom,
            officeFrom: $this->officeFrom,
            phone: $this->phone,
            email: $this->email,
            telAddress: $this->telAddress,
            telAddressFrom: $this->telAddressFrom,
            declaredValue: $this->declaredValue,
            insrValue: $this->insrValue,
            compulsoryPayment: $this->compulsoryPayment,
            cashOnDelivery: $this->cashOnDelivery,
            payment: $this->payment,
            fragile: $this->fragile,
            mailDirect: $this->mailDirect,
            mailRank: $this->mailRank,
            tariffCount: $this->tariffCount,
            dimension: $this->dimension,
            dimensionType: $this->dimensionType,
            customsDeclaration: $this->customsDeclaration,
            declarationStatus: $this->declarationStatus,
            addToMmo: $this->addToMmo,
            courier: $this->courier,
            completenessChecking: $this->completenessChecking,
            prePostalPreparation: $this->prePostalPreparation,
            deliveryWithCod: $this->deliveryWithCod,
            totalRateWoVat: $this->totalRateWoVat,
            totalVat: $this->totalVat,
            massRate: $this->massRate,
            massRateWithVat: $this->massRateWithVat,
            massRateWoVat: $this->massRateWoVat,
            aviRate: $this->aviRate,
            aviRateWithVat: $this->aviRateWithVat,
            aviRateWoVat: $this->aviRateWoVat,
            groundRate: $this->groundRate,
            groundRateWithVat: $this->groundRateWithVat,
            groundRateWoVat: $this->groundRateWoVat,
            insrRate: $this->insrRate,
            insrRateWithVat: $this->insrRateWithVat,
            insrRateWoVat: $this->insrRateWoVat,
            fragileRateWithVat: $this->fragileRateWithVat,
            fragileRateWoVat: $this->fragileRateWoVat,
            completenessCheckingRateWithVat: $this->completenessCheckingRateWithVat,
            completenessCheckingRateWoVat: $this->completenessCheckingRateWoVat,
            contentsCheckingRateWithVat: $this->contentsCheckingRateWithVat,
            contentsCheckingRateWoVat: $this->contentsCheckingRateWoVat,
            functionalityCheckingRateWithVat: $this->functionalityCheckingRateWithVat,
            functionalityCheckingRateWoVat: $this->functionalityCheckingRateWoVat,
            withFittingRateWithVat: $this->withFittingRateWithVat,
            withFittingRateWoVat: $this->withFittingRateWoVat,
            noticeRateWithVat: $this->noticeRateWithVat,
            noticeRateWoVat: $this->noticeRateWoVat,
            inventoryRateWithVat: $this->inventoryRateWithVat,
            inventoryRateWoVat: $this->inventoryRateWoVat,
            prePotalPreparationRateWithVat: $this->prePotalPreparationRateWithVat,
            prePotalPreparationRateWoVat: $this->prePotalPreparationRateWoVat,
            oversizeRateWithVat: $this->oversizeRateWithVat,
            oversizeRateWoVat: $this->oversizeRateWoVat,
            partialRedempationRateWithVat: $this->partialRedempationRateWithVat,
            partialRedempationRateWoVat: $this->partialRedempationRateWoVat,
            easyReturnRateWithVat: $this->easyReturnRateWithVat,
            easyReturnRateWoVat: $this->easyReturnRateWoVat,
            vsdRateWithVat: $this->vsdRateWithVat,
            vsdRateWoVat: $this->vsdRateWoVat,
            smsNoticeRecipientRateWithVat: $this->smsNoticeRecipientRateWithVat,
            smsNoticeRecipientRateWoVat: $this->smsNoticeRecipientRateWoVat,
            status: $this->status,
            transportType: $this->transportType,
            transportMode: $this->transportMode,
            paymentMethod: $this->paymentMethod,
            noticePaymentMethod: $this->noticePaymentMethod,
            comment: $this->comment,
            groupName: $this->groupName,
            postofficeCode: $this->postofficeCode,
            branchName: $this->branchName,
            inMmo: $this->inMmo,
            tender: $this->tender,
            senderName: $this->senderName,
            senderComment: $this->senderComment,
            rawAddress: $this->rawAddress,
            addressChanged: $this->addressChanged,
            linkedBarcode: $this->linkedBarcode,
            shelfLifeDays: $this->shelfLifeDays,
            bkHash: $this->bkHash,
            smsNoticeRecipient: $this->smsNoticeRecipient,
            postmarks: $this->postmarks,
        );
    }
}
