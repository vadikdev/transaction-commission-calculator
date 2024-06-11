<?php

namespace App\Service\CommissionRateProvider\Providers;

use App\Service\CommissionRateProvider\CommissionRateProviderInterface;

class DefaultCommissionRateProvider implements CommissionRateProviderInterface
{
    public function supports(string $countryCode): bool
    {
        return true;
    }

    public function getCommissionRate(): float
    {
        return $_ENV['APP_DEFAULT_COMMISSION_RATE'];
    }
}
