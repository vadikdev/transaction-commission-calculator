<?php

namespace App\Service\ExchangeRateProvider;

use App\Service\ExchangeRateProvider\Exception\ExchangeRateProviderException;

interface ExchangeRateProviderInterface
{
    /**
     * @throws ExchangeRateProviderException
     */
    public function getRateForCurrency(string $currencyCode, string $baseCurrencyCode): float;
}
