<?php

namespace App\Service\CommissionCalculator;

use App\DTO\TransactionDTO;
use App\Service\BinDecoder\BinDecoderInterface;
use App\Service\CommissionRateProvider\CommissionRateProvider;
use App\Service\ExchangeRateProvider\ExchangeRateProviderInterface;

class CommissionCalculator
{
    public function __construct(
        protected BinDecoderInterface $binDecoder,
        protected ExchangeRateProviderInterface $exchangeRateProvider,
        protected CommissionRateProvider $commissionRateProvider,
    ) {
    }

    public function calculateForTransaction(TransactionDTO $transactionDTO): float
    {
        $exchangeRate = $this->exchangeRateProvider
            ->getRateForCurrency($transactionDTO->currency, $_ENV['APP_BASE_CURRENCY']);
        $countryCode = $this->binDecoder->getCountryAlpha2($transactionDTO->bin);
        $commissionRate = $this->commissionRateProvider->getForCountryCode($countryCode);

        $amount = $transactionDTO->amount / $exchangeRate;
        $amount *= $commissionRate;

        return round($amount, 2);
    }
}
