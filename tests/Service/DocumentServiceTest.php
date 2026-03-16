<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Tests\Service;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use SergeR\RussianPostSDK\{Client, Config};
use SergeR\RussianPostSDK\Dto\Response\BinaryResponse;

final class DocumentServiceTest extends TestCase
{
    private Psr17Factory $factory;
    private MockClient $mockClient;
    private Client $client;

    protected function setUp(): void
    {
        $this->factory = new Psr17Factory();
        $this->mockClient = new MockClient();

        $config = new Config('token123', 'user@example.com', 'secret');
        $this->client = new Client(
            $config,
            $this->mockClient,
            $this->factory,
            $this->factory,
        );
    }

    public function testF7PdfReturnsBinaryResponse(): void
    {
        $pdfContent = '%PDF-1.4 fake pdf content';

        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/pdf')
                ->withBody($this->factory->createStream($pdfContent))
        );

        $result = $this->client->documents()->f7pdf(123);

        self::assertInstanceOf(BinaryResponse::class, $result);
        self::assertSame($pdfContent, $result->content);
        self::assertSame('application/pdf', $result->mimeType);
    }

    public function testDownloadAllReturnsBinaryResponse(): void
    {
        $zipContent = 'PK fake zip content';

        $this->mockClient->addResponse(
            $this->factory->createResponse(200)
                ->withHeader('Content-Type', 'application/zip')
                ->withBody($this->factory->createStream($zipContent))
        );

        $result = $this->client->documents()->downloadAll('batch-001');

        self::assertInstanceOf(BinaryResponse::class, $result);
        self::assertSame($zipContent, $result->content);
        self::assertSame('application/zip', $result->mimeType);
    }
}
