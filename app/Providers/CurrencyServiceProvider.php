<?php

namespace App\Providers;

use App\DataProviders\ApiLayerCurrencyProvider;
use App\DataProviders\Contracts\CurrencyProviderInterface;
use App\DataProviders\ExceptionHandlers\ApiExceptionHandlerInterface;
use App\DataProviders\ExceptionHandlers\ApiLayerExceptionHandler;
use App\ResponseFormatters\Contracts\CurrencyResponseHandlerInterface;
use App\ResponseFormatters\JsonCurrencyResponseFormatter;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CurrencyProviderInterface::class, ApiLayerCurrencyProvider::class);
        $this->app->bind(CurrencyResponseHandlerInterface::class, JsonCurrencyResponseFormatter::class);

        $this->app->when(ApiLayerCurrencyProvider::class)
            ->needs(ClientInterface::class)
            ->give(fn() => new Client([
                'headers' => [
                    'apiKey' => env('API_LAYER_CURRENCY_API_KEY')
                ]
            ]));

        $this->app->when(ApiLayerCurrencyProvider::class)
            ->needs(ApiExceptionHandlerInterface::class)
            ->give(ApiLayerExceptionHandler::class);
    }
}
