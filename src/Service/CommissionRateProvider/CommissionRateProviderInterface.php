<?php

namespace App\Service\CommissionRateProvider;

interface CommissionRateProviderInterface
{
    public function supports(string $countryCode): bool;

    public function getCommissionRate(): float;
}
