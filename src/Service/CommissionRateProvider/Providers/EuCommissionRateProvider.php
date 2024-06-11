<?php

namespace App\Service\CommissionRateProvider\Providers;

use App\Service\CommissionRateProvider\CommissionRateProviderInterface;

class EuCommissionRateProvider implements CommissionRateProviderInterface
{
    public function supports(string $countryCode): bool
    {
        return str_contains($_ENV['APP_EU_COUNTRY_CODES'], $countryCode);
    }

    public function getCommissionRate(): float
    {
        return $_ENV['APP_EU_COMMISSION_RATE'];
    }
}
