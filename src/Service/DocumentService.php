<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Service;

use SergeR\RussianPostSDK\Dto\Response\BinaryResponse;
use SergeR\RussianPostSDK\Enums\{PrintType, PrintSide};
use SergeR\RussianPostSDK\Http\HttpTransport;

final class DocumentService
{
    public function __construct(private readonly HttpTransport $transport) {}

    /**
     * Download all forms for batch
     */
    public function downloadAll(string $batchName, ?PrintType $printType = null, ?PrintSide $printTypeForms = null): BinaryResponse
    {
        $query = [];
        if ($printType !== null) {
            $query['print-type'] = $printType->value;
        }
        if ($printTypeForms !== null) {
            $query['print-type-forms'] = $printTypeForms->value;
        }

        $response = $this->transport->send('GET', "/1.0/forms/{$batchName}/zip-all", query: $query, expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }

    /**
     * Download form 7 PDF
     */
    public function f7pdf(int $orderId, ?string $sendingDate = null, ?PrintType $printType = null): BinaryResponse
    {
        $query = [];
        if ($sendingDate !== null) {
            $query['sending-date'] = $sendingDate;
        }
        if ($printType !== null) {
            $query['print-type'] = $printType->value;
        }

        $response = $this->transport->send('GET', "/1.0/forms/{$orderId}/f7pdf", query: $query, expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }

    /**
     * Download form 112 PDF
     */
    public function f112pdf(int $orderId, ?string $sendingDate = null): BinaryResponse
    {
        $query = [];
        if ($sendingDate !== null) {
            $query['sending-date'] = $sendingDate;
        }

        $response = $this->transport->send('GET', "/1.0/forms/{$orderId}/f112pdf", query: $query, expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }

    /**
     * Download forms before batch
     */
    public function formsBeforeBatch(int $orderId, ?string $sendingDate = null): BinaryResponse
    {
        $query = [];
        if ($sendingDate !== null) {
            $query['sending-date'] = $sendingDate;
        }

        $response = $this->transport->send('GET', "/1.0/forms/backlog/{$orderId}/forms", query: $query, expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }

    /**
     * Download forms for order
     */
    public function forms(int $orderId, ?string $sendingDate = null, ?PrintType $printType = null): BinaryResponse
    {
        $query = [];
        if ($sendingDate !== null) {
            $query['sending-date'] = $sendingDate;
        }
        if ($printType !== null) {
            $query['print-type'] = $printType->value;
        }

        $response = $this->transport->send('GET', "/1.0/forms/{$orderId}/forms", query: $query, expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }

    /**
     * Download form 103 PDF
     */
    public function f103pdf(string $batchName): BinaryResponse
    {
        $response = $this->transport->send('GET', "/1.0/forms/{$batchName}/f103pdf", expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }

    /**
     * Download completeness checking form
     */
    public function completenessCheckingForm(string $batchId): BinaryResponse
    {
        $response = $this->transport->send('GET', "/1.0/forms/{$batchId}/completeness-checking-form", expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }

    /**
     * Download easy return PDF
     */
    public function easyReturnPdf(string $barcode, ?PrintType $printType = null): BinaryResponse
    {
        $query = [];
        if ($printType !== null) {
            $query['print-type'] = $printType->value;
        }

        $response = $this->transport->send('GET', "/1.0/forms/{$barcode}/easy-return-pdf", query: $query, expectBinary: true);
        assert($response instanceof BinaryResponse);
        return $response;
    }
}
