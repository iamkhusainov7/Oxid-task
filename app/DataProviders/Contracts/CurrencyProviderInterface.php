<?php

namespace App\DataProviders\Contracts;

use App\Entities\CurrencyEntity;

interface CurrencyProviderInterface
{
    public function getCurrencyRates(CurrencyEntity $baseCurrency, CurrencyEntity ...$currencies): CurrencyEntity;
}
