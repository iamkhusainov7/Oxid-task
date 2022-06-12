<?php

namespace App\ResponseFormatters\Contracts;

use App\Entities\CurrencyEntity;
use Symfony\Component\HttpFoundation\Response;

interface CurrencyResponseHandlerInterface
{
    public function format(CurrencyEntity $currencyEntity): Response;
}
