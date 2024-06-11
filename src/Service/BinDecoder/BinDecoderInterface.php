<?php

namespace App\Service\BinDecoder;

use App\Service\BinDecoder\Exception\BinDecoderException;

interface BinDecoderInterface
{
    /**
     * @throws BinDecoderException
     */
    public function getCountryAlpha2(string $bin): string;
}
