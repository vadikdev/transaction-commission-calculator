<?php

namespace App\Tests\Service\CommissionRateProvider\Providers;

use App\Service\CommissionRateProvider\Providers\EuCommissionRateProvider;
use PHPUnit\Framework\TestCase;

class EuCommissionRateProviderTest extends TestCase
{
    protected EuCommissionRateProvider $euCommissionRateProvider;

    public function setUp(): void
    {
        $this->euCommissionRateProvider = new EuCommissionRateProvider();
        parent::setUp();
    }

    public function testSupports(): void
    {
        foreach ($this->getEuCountryCodes() as $countryCode) {
            $this->assertTrue($this->euCommissionRateProvider->supports($countryCode));
        }
    }

    public function testSupportsNot(): void
    {
        $this->assertFalse($this->euCommissionRateProvider->supports('TS'));
        $this->assertFalse($this->euCommissionRateProvider->supports('US'));
        $this->assertFalse($this->euCommissionRateProvider->supports('CA'));
    }

    public function testGetCommissionRate(): void
    {
        $this->assertSame(0.011, $this->euCommissionRateProvider->getCommissionRate());
    }

    public function getEuCountryCodes(): array
    {
        return ['LT', 'EE', 'LV'];
    }
}
