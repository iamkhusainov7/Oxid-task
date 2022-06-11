<?php

namespace App\ResponseFormatters\Contracts;

use App\Entities\CurrencyEntity;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Lumen\Http\ResponseFactory;

interface CurrencyResponseHandlerInterface
{
    public function handle(CurrencyEntity $currencyEntity): Response|ResponseFactory;
}
