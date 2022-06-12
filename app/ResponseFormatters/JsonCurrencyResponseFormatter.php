<?php

namespace App\ResponseFormatters;

use App\Dtos\CurrencyRateDto;
use App\Entities\CurrencyEntity;
use App\ResponseFormatters\Contracts\CurrencyResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class JsonCurrencyResponseFormatter implements CurrencyResponseHandlerInterface
{
    public function format(CurrencyEntity $currencyEntity): Response
    {
        return response()->json(CurrencyRateDto::fromEntity($currencyEntity));
    }
}
