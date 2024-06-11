<?php

namespace App\Service\CommissionRateProvider;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class CommissionRateProvider
{
    /**
     * @var iterable|CommissionRateProviderInterface[]
     */
    protected iterable $commissionRateProviders;

    public function __construct(#[AutowireIterator('app.commission_rate_provider')] iterable $commissionRateProviders)
    {
        $this->commissionRateProviders = $commissionRateProviders;
    }

    public function getForCountryCode(string $countryCode): ?float
    {
        foreach ($this->commissionRateProviders as $commissionRateProvider) {
            if ($commissionRateProvider->supports($countryCode)) {
                return $commissionRateProvider->getCommissionRate();
            }
        }

        throw new MissingCommissionRateProviderException(sprintf('Missing commission rate provider for country code %s', $countryCode));
    }
}
