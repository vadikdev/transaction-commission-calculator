<?php

namespace App\Tests\Service\BinListBridge;

use App\Service\BinDecoder\Exception\BinDecoderException;
use App\Service\BinList\BinListClient;
use App\Service\BinList\Transfer\CountryTransfer;
use App\Service\BinList\Transfer\LookupResponseTransfer;
use App\Service\BinListBridge\BinListBinDecoder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class BinListBinDecoderTest extends TestCase
{
    protected BinListClient|MockObject $binListClient;
    protected BinListBinDecoder $binListBinDecoder;

    public function setUp(): void
    {
        parent::setUp();
        $this->binListClient = $this->createMock(BinListClient::class);
        $this->binListBinDecoder = new BinListBinDecoder($this->binListClient);
    }

    public function testGetCountryAlpha2BinListLookupHttpException(): void
    {
        $exception = $this->createMock(HttpExceptionInterface::class);
        $this->binListClient->expects(self::once())
            ->method('lookup')
            ->with('123')
            ->willThrowException($exception)
        ;

        $this->expectException(BinDecoderException::class);
        $this->binListBinDecoder->getCountryAlpha2('123');
    }

    public function testGetCountryAlpha2BinListLookupEmptyResponse(): void
    {
        $this->binListClient->expects(self::once())
            ->method('lookup')
            ->with('123')
            ->willReturn(new LookupResponseTransfer())
        ;

        $this->expectException(BinDecoderException::class);
        $this->expectExceptionMessage('Unable to find country code for bin 123');
        $this->binListBinDecoder->getCountryAlpha2('123');
    }

    public function testGetCountrySuccess(): void
    {
        $lookupResponseTransfer = new LookupResponseTransfer();
        $countryTransfer = new CountryTransfer();
        $countryTransfer->alpha2 = 'ts';
        $lookupResponseTransfer->country = $countryTransfer;

        $this->binListClient->expects(self::once())
            ->method('lookup')
            ->with('123')
            ->willReturn($lookupResponseTransfer)
        ;

        $alpha2 = $this->binListBinDecoder->getCountryAlpha2('123');
        $this->assertSame('ts', $alpha2);
    }
}
