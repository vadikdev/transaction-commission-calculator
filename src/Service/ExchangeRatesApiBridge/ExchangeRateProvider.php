<?php

namespace App\Service\ExchangeRatesApiBridge;

use App\Service\ExchangeRateProvider\Exception\ExchangeRateProviderException;
use App\Service\ExchangeRateProvider\ExchangeRateProviderInterface;
use App\Service\ExchangeRatesApi\ExchangeRatesApiClient;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    public function __construct(protected ExchangeRatesApiClient $exchangeRatesApiClient)
    {
    }

    public function getRateForCurrency(string $currencyCode, string $baseCurrencyCode): float
    {
        if ($currencyCode === $baseCurrencyCode) {
            return 1;
        }

        try {
            $responseTransfer = $this->exchangeRatesApiClient
                ->latest([$currencyCode], $baseCurrencyCode)
            ;
        } catch (HttpExceptionInterface $exception) {
            throw new ExchangeRateProviderException(sprintf('ExchangeRatesApi HttpException: %s', $exception->getMessage()));
        }

        if (!isset($responseTransfer->rates[$currencyCode])) {
            throw new ExchangeRateProviderException(sprintf('Unable to retrieve exchange rate for %s with base currency %s', $currencyCode, $baseCurrencyCode));
        }

        return $responseTransfer->rates[$currencyCode] ?: 1;
    }
}
