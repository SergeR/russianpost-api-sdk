<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Request;

/**
 * Реквизиты получателя наложенного платежа.
 *
 * @see https://otpravka-api.pochta.ru/specification
 */
final readonly class PaymentDetails
{
    public function __construct(
        public ?string $recipientSurname = null,
        public ?string $recipientFirstname = null,
        public ?string $recipientMiddlename = null,
        public ?string $bankName = null,
        public ?string $bankBik = null,
        public ?string $bankCorrAccount = null,
        public ?string $bankAccount = null,
        public ?string $isCash = null,
    ) {}

    /**
     * Convert to array for API request.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->recipientSurname !== null) {
            $data['recipient-surname'] = $this->recipientSurname;
        }
        if ($this->recipientFirstname !== null) {
            $data['recipient-firstname'] = $this->recipientFirstname;
        }
        if ($this->recipientMiddlename !== null) {
            $data['recipient-middlename'] = $this->recipientMiddlename;
        }
        if ($this->bankName !== null) {
            $data['bank-name'] = $this->bankName;
        }
        if ($this->bankBik !== null) {
            $data['bank-bik'] = $this->bankBik;
        }
        if ($this->bankCorrAccount !== null) {
            $data['bank-corr-account'] = $this->bankCorrAccount;
        }
        if ($this->bankAccount !== null) {
            $data['bank-account'] = $this->bankAccount;
        }
        if ($this->isCash !== null) {
            $data['is-cash'] = $this->isCash;
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
            recipientSurname: $data['recipient-surname'] ?? null,
            recipientFirstname: $data['recipient-firstname'] ?? null,
            recipientMiddlename: $data['recipient-middlename'] ?? null,
            bankName: $data['bank-name'] ?? null,
            bankBik: $data['bank-bik'] ?? null,
            bankCorrAccount: $data['bank-corr-account'] ?? null,
            bankAccount: $data['bank-account'] ?? null,
            isCash: $data['is-cash'] ?? null,
        );
    }
}
