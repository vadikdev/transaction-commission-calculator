<?php

namespace App\Tests\Service\CommissionRateProvider;

use App\Service\CommissionRateProvider\CommissionRateProvider;
use App\Service\CommissionRateProvider\CommissionRateProviderInterface;
use App\Service\CommissionRateProvider\MissingCommissionRateProviderException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommissionRateProviderTest extends TestCase
{
    protected CommissionRateProviderInterface|MockObject $provider1;
    protected CommissionRateProviderInterface|MockObject $provider2;
    protected CommissionRateProvider $commissionRateProvider;

    public function setUp(): void
    {
        $this->provider1 = $this->createMock(CommissionRateProviderInterface::class);
        $this->provider2 = $this->createMock(CommissionRateProviderInterface::class);
        $this->commissionRateProvider = new CommissionRateProvider([$this->provider1, $this->provider2]);

        parent::setUp();
    }

    public function testGetForCountryCodeFirstProviderSupports(): void
    {
        $this->provider1->expects(self::once())
            ->method('supports')
            ->with('TS')
            ->willReturn(true);

        $this->provider1->expects(self::once())
            ->method('getCommissionRate')
            ->willReturn(0.011);

        $this->provider2->expects(self::never())
            ->method('supports');

        $this->provider2->expects(self::never())
            ->method('getCommissionRate');

        $commissionRate = $this->commissionRateProvider->getForCountryCode('TS');
        $this->assertSame(0.011, $commissionRate);
    }

    public function testGetForCountryCodeSecondProviderSupports(): void
    {
        $this->provider1->expects(self::once())
            ->method('supports')
            ->willReturn(false)
        ;

        $this->provider1->expects(self::never())
            ->method('getCommissionRate');

        $this->provider2->expects(self::once())
            ->method('supports')
            ->with('TS')
            ->willReturn(true);

        $this->provider2->expects(self::once())
            ->method('getCommissionRate')
            ->willReturn(0.022);

        $commissionRate = $this->commissionRateProvider->getForCountryCode('TS');
        $this->assertSame(0.022, $commissionRate);
    }

    public function testGetForCountryCodeNoneProviderSupports(): void
    {
        $this->provider1->expects(self::once())
            ->method('supports')
            ->willReturn(false);

        $this->provider1->expects(self::never())
            ->method('getCommissionRate');

        $this->provider2->expects(self::once())
            ->method('supports')
            ->willReturn(false);

        $this->provider2->expects(self::never())
            ->method('getCommissionRate');

        $this->expectException(MissingCommissionRateProviderException::class);
        $this->expectExceptionMessage('Missing commission rate provider for country code TS');

        $this->commissionRateProvider->getForCountryCode('TS');
    }
}
