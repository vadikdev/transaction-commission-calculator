<?php

namespace App\Service\BinList\Transfer;

class CountryTransfer extends AbstractTransfer
{
    public ?string $numeric = null;
    public ?string $alpha2 = null;
    public ?string $name = null;
    public ?string $emoji = null;
    public ?string $currency = null;
    public ?int $latitude = null;
    public ?int $longitude = null;
}
