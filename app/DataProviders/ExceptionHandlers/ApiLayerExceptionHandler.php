<?php

namespace App\DataProviders\ExceptionHandlers;

use App\Exceptions\CurrencyNotFoundException;
use GuzzleHttp\Exception\RequestException;

class ApiLayerExceptionHandler implements ApiExceptionHandlerInterface
{
    public const INVALID_BASE_CURRENCY_CURRENCY_ERROR = 'invalid_base_currency';
    public const INVALID_CURRENCY_CODES_ERROR = 'invalid_currency_codes';

    public function handle(RequestException $exception): void
    {
        $body = json_decode($exception->getResponse()->getBody(), true);

        match ($body['code'] ?? '') {
            self::INVALID_BASE_CURRENCY_CURRENCY_ERROR => new CurrencyNotFoundException('Invalid base currency was provided!', $exception->getRequest(), $exception->getResponse()),
            self::INVALID_CURRENCY_CODES_ERROR => new CurrencyNotFoundException('One of the rate currencies is invalid!', $exception->getRequest(), $exception->getResponse()),
            default => ''
        };

        throw $exception;
    }
}
