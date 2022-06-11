<?php

namespace App\ResponseFormatters;

use App\Dtos\CurrencyRateDto;
use App\Entities\CurrencyEntity;
use App\ResponseFormatters\Contracts\CurrencyResponseHandlerInterface;
use Laravel\Lumen\Http\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class JsonCurrencyResponseFormatter implements CurrencyResponseHandlerInterface
{
    public function handle(CurrencyEntity $currencyEntity): Response|ResponseFactory
    {
        return response()->json(CurrencyRateDto::fromEntity($currencyEntity));
    }
}
