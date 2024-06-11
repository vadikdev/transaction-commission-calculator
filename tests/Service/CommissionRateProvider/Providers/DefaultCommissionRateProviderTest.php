<?php

namespace App\Tests\Service\CommissionRateProvider\Providers;

use App\Service\CommissionRateProvider\Providers\DefaultCommissionRateProvider;
use PHPUnit\Framework\TestCase;

class DefaultCommissionRateProviderTest extends TestCase
{
    protected DefaultCommissionRateProvider $defaultCommissionRateProvider;

    public function setUp(): void
    {
        $this->defaultCommissionRateProvider = new DefaultCommissionRateProvider();
        parent::setUp();
    }

    public function testSupports(): void
    {
        foreach ($this->getEuCountryCodes() as $countryCode) {
            $this->assertTrue($this->defaultCommissionRateProvider->supports($countryCode));
        }
    }

    public function testGetCommissionRate(): void
    {
        $this->assertSame(0.022, $this->defaultCommissionRateProvider->getCommissionRate());
    }

    public function getEuCountryCodes(): array
    {
        return ['LT', 'EE', 'LV', 'UA', 'US'];
    }
}
