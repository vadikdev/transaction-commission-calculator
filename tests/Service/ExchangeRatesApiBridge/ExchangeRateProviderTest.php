<?php

namespace App\Tests\Service\ExchangeRatesApiBridge;

use App\Service\ExchangeRateProvider\Exception\ExchangeRateProviderException;
use App\Service\ExchangeRatesApi\ExchangeRatesApiClient;
use App\Service\ExchangeRatesApi\Transfer\ResponseTransfer;
use App\Service\ExchangeRatesApiBridge\ExchangeRateProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class ExchangeRateProviderTest extends TestCase
{
    protected ExchangeRatesApiClient|MockObject $exchangeRatesApiClient;
    protected ExchangeRateProvider $exchangeRateProvider;

    public function setUp(): void
    {
        parent::setUp();
        $this->exchangeRatesApiClient = $this->createMock(ExchangeRatesApiClient::class);
        $this->exchangeRateProvider = new ExchangeRateProvider($this->exchangeRatesApiClient);
    }

    public function testGetRateForCurrencySameCurrencyCodes(): void
    {
        $this->exchangeRatesApiClient->expects(self::never())->method('latest');

        $rate = $this->exchangeRateProvider->getRateForCurrency('TST', 'TST');

        $this->assertSame(1.0, $rate);
    }

    public function testGetRateForCurrencyExchangeRatesApiHttpException(): void
    {
        $exception = $this->createMock(HttpExceptionInterface::class);

        $this->exchangeRatesApiClient->expects(self::once())
            ->method('latest')
            ->with(['TST'], 'EUR')
            ->willThrowException($exception)
        ;

        $this->expectException(ExchangeRateProviderException::class);
        $this->exchangeRateProvider->getRateForCurrency('TST', 'EUR');
    }

    public function testGetRateForCurrencyExchangeRatesApiEmptyResponse(): void
    {
        $this->exchangeRatesApiClient->expects(self::once())
            ->method('latest')
            ->with(['TST'], 'EUR')
            ->willReturn(new ResponseTransfer())
        ;

        $this->expectException(ExchangeRateProviderException::class);
        $this->expectExceptionMessage('Unable to retrieve exchange rate for TST with base currency EUR');
        $this->exchangeRateProvider->getRateForCurrency('TST', 'EUR');
    }

    public function testGetRateForCurrencyExchangeRateZero(): void
    {
        $responseTransfer = new ResponseTransfer();
        $responseTransfer->rates['TST'] = 0;

        $this->exchangeRatesApiClient->expects(self::once())
            ->method('latest')
            ->with(['TST'], 'EUR')
            ->willReturn($responseTransfer)
        ;

        $rate = $this->exchangeRateProvider->getRateForCurrency('TST', 'EUR');
        $this->assertSame(1.0, $rate);
    }

    public function testGetRateForCurrencyExchangeRateNonZero(): void
    {
        $responseTransfer = new ResponseTransfer();
        $responseTransfer->rates['TST'] = 1.23;
        $this->exchangeRatesApiClient->expects(self::once())
            ->method('latest')
            ->with(['TST'], 'EUR')
            ->willReturn($responseTransfer)
        ;

        $rate = $this->exchangeRateProvider->getRateForCurrency('TST', 'EUR');
        $this->assertSame(1.23, $rate);
    }
}
