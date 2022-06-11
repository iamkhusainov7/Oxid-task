<?php

namespace App\DataProviders\ExceptionHandlers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Throwable;

interface ApiExceptionHandlerInterface
{
    /**
     * @param RequestException $exception
     *
     * @return void
     *
     * @throws Throwable
     */
    public function handle(RequestException $exception): void;
}
