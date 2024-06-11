<?php

namespace App\Service\BinList\Transfer;

class LookupResponseTransfer extends AbstractTransfer
{
    public ?array $number = null;
    public ?string $scheme = null;
    public ?string $type = null;
    public ?string $brand = null;
    public ?CountryTransfer $country = null;
    public ?BankTransfer $bank = null;
}
