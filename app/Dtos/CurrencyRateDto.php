<?php

namespace App\Dtos;

use App\Entities\CurrencyEntity;

class CurrencyRateDto extends DtoTemplate
{
    public string $baseCurrencyCode;
    public float $amount;
    public array $currencies = [];

    public static function fromEntity(CurrencyEntity $currencyEntity)
    {
        return new static([
            'baseCurrencyCode' => $currencyEntity->getCode(),
            'amount' => $currencyEntity->getValue(),
            'currencies' => $currencyEntity->getCurrenciesRates()?->map(fn (CurrencyEntity $entity) => static::fromEntity($entity))->toArray() ?? [],
        ]);
    }
}
