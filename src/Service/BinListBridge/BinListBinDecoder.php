<?php

namespace App\Service\BinListBridge;

use App\Service\BinDecoder\BinDecoderInterface;
use App\Service\BinDecoder\Exception\BinDecoderException;
use App\Service\BinList\BinListClient;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class BinListBinDecoder implements BinDecoderInterface
{
    public function __construct(protected BinListClient $binListClient)
    {
    }

    public function getCountryAlpha2(string $bin): string
    {
        try {
            $lookupTransfer = $this->binListClient->lookup($bin);
        } catch (HttpExceptionInterface $exception) {
            throw new BinDecoderException(sprintf('BinList Lookup HttpException: %s', $exception->getMessage()));
        }

        if (!$lookupTransfer->country?->alpha2) {
            throw new BinDecoderException(sprintf('Unable to find country code for bin %s', $bin));
        }

        return $lookupTransfer->country->alpha2;
    }
}
