<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto\Response;

final readonly class BinaryResponse
{
    public function __construct(
        public string $content,
        public string $mimeType,
    ) {}

    public function saveAs(string $filePath): void
    {
        file_put_contents($filePath, $this->content);
    }
}
