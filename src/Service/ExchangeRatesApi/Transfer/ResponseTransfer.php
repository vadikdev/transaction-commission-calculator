<?php

namespace App\Service\ExchangeRatesApi\Transfer;

class ResponseTransfer extends AbstractTransfer
{
    public ?bool $success = null;
    public ?int $timestamp = null;
    public ?string $base = null;
    public ?string $date = null;
    public ?array $rates = null;
}
