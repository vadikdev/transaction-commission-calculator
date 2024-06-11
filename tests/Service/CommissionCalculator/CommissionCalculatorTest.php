<?php

namespace App\Tests\Service\CommissionCalculator;

use App\DTO\TransactionDTO;
use App\Service\BinDecoder\BinDecoderInterface;
use App\Service\CommissionCalculator\CommissionCalculator;
use App\Service\CommissionRateProvider\CommissionRateProvider;
use App\Service\ExchangeRateProvider\ExchangeRateProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    protected BinDecoderInterface|MockObject $binDecoder;
    protected ExchangeRateProviderInterface|MockObject $exchangeRateProvider;
    protected CommissionRateProvider|MockObject $commissionRateProvider;
    protected CommissionCalculator $commissionCalculator;

    public function setUp(): void
    {
        $this->binDecoder = $this->createMock(BinDecoderInterface::class);
        $this->exchangeRateProvider = $this->createMock(ExchangeRateProviderInterface::class);
        $this->commissionRateProvider = $this->createMock(CommissionRateProvider::class);
        $this->commissionCalculator = new CommissionCalculator(
            $this->binDecoder,
            $this->exchangeRateProvider,
            $this->commissionRateProvider
        );

        parent::setUp();
    }

    public function testCalculateForTransaction(): void
    {
        $transactionDTO = new TransactionDTO();
        $transactionDTO->currency = 'UAH';
        $transactionDTO->bin = '01234';
        $transactionDTO->amount = 800.01;

        $this->exchangeRateProvider->expects(self::once())
            ->method('getRateForCurrency')
            ->with('UAH', 'EUR')
            ->willReturn(40.3);

        $this->binDecoder->expects(self::once())
            ->method('getCountryAlpha2')
            ->with('01234')
            ->willReturn('UA');

        $this->commissionRateProvider->expects(self::once())
            ->method('getForCountryCode')
            ->with('UA')
            ->willReturn(0.012);

        $commission = $this->commissionCalculator->calculateForTransaction($transactionDTO);
        $this->assertSame(0.24, $commission);
    }
}
