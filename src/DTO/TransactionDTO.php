<?php

namespace App\DTO;

class TransactionDTO
{
    public ?string $bin = null;
    public ?float $amount = null;
    public ?string $currency = null;
}
