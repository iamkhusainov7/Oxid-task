<?php

namespace Tests\DataProviders;

use App\DataProviders\ApiLayerCurrencyProvider;
use App\DataProviders\ExceptionHandlers\ApiLayerExceptionHandler;
use App\Entities\CurrencyEntity;
use App\Exceptions\CurrencyNotFoundException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class ApiLayerCurrencyProviderTest extends TestCase
{
    public function testGetCurrencyRatesWithProperParameters()
    {
        $responseStructure = [
            "success"   => true,
            "timestamp" => 1654984204,
            "base"      => "EUR",
            "date"      => "2022-06-11",
            "rates"     => [
                "AED" => 3.863315,
                "AFN" => 94.028625,
            ],
        ];
        $response          = new Response(body: json_encode($responseStructure));
        $client            = $this->createMock(ClientInterface::class);
        $client->method('request')
            ->willReturn($response);

        $amount         = 10.0;
        $service        = new ApiLayerCurrencyProvider($client, $this->app->make(ApiLayerExceptionHandler::class));
        $currencyToRate = new CurrencyEntity('AED', $amount, new \DateTime());
        $currency       = $service->getCurrencyRates(
            new CurrencyEntity('EUR', $amount, new \DateTime()),
            $currencyToRate
        );

        $this->assertTrue($currency->getValue() === $amount);
        $this->assertTrue($currency->getCurrenciesRates()->first()->getValue() === $responseStructure['rates']['AED'] * $currency->getValue());
        $this->assertTrue($currency->getCurrenciesRates()->last()->getValue() === $responseStructure['rates']['AFN'] * $currency->getValue());
    }

    public function testGetCurrencyRatesWithWrongBaseCurrency()
    {
        $responseStructure = [
            "error" => [
                "code"    => "invalid_base_currency",
                "message" => "An unexpected error ocurred. [Technical Support: support@apilayer.com]",
            ],
        ];
        $response          = new Response(status: 400, body: json_encode($responseStructure));
        $client            = $this->createMock(ClientInterface::class);
        $clientException   = $this->createMock(RequestException::class);

        $clientException->method('getRequest')
            ->willReturn(new Request("GET", ''));

        $clientException->method('getResponse')
            ->willReturn($response);

        $client->method('request')
            ->willThrowException($clientException);

        $amount         = 10.0;
        $service        = new ApiLayerCurrencyProvider($client, $this->app->make(ApiLayerExceptionHandler::class));
        $currencyToRate = new CurrencyEntity('AED', $amount, new \DateTime());
        $this->expectException(CurrencyNotFoundException::class);
        $service->getCurrencyRates(
            new CurrencyEntity('EUR', $amount, new \DateTime()),
            $currencyToRate
        );
    }

    public function testGetCurrencyRatesWithWrongRateCurrency()
    {
        $responseStructure = [
            "error" => [
                "code"    => "invalid_currency_codes",
                "message" => "You have provided one or more invalid Currency Codes. [Required format: currencies=EUR,USD,GBP,...]",
            ],
        ];
        $response          = new Response(status: 400, body: json_encode($responseStructure));
        $client            = $this->createMock(ClientInterface::class);
        $clientException   = $this->createMock(RequestException::class);

        $clientException->method('getRequest')
            ->willReturn(new Request("GET", ''));

        $clientException->method('getResponse')
            ->willReturn($response);

        $client->method('request')
            ->willThrowException($clientException);

        $amount         = 10.0;
        $service        = new ApiLayerCurrencyProvider($client, $this->app->make(ApiLayerExceptionHandler::class));
        $currencyToRate = new CurrencyEntity('AED', $amount, new \DateTime());
        $this->expectException(CurrencyNotFoundException::class);
        $service->getCurrencyRates(
            new CurrencyEntity('EUR', $amount, new \DateTime()),
            $currencyToRate
        );
    }

    public function testUnauthorized()
    {
        $responseStructure = [
            "message" => 'No API key found in request',
        ];
        $response          = new Response(status: 401, body: json_encode($responseStructure));
        $client            = $this->createMock(ClientInterface::class);
        $clientException   = $this->createMock(RequestException::class);

        $clientException->method('getRequest')
            ->willReturn(new Request("GET", ''));

        $clientException->method('getResponse')
            ->willReturn($response);

        $client->method('request')
            ->willThrowException($clientException);

        $amount         = 10.0;
        $service        = new ApiLayerCurrencyProvider($client, $this->app->make(ApiLayerExceptionHandler::class));
        $currencyToRate = new CurrencyEntity('AED', $amount, new \DateTime());
        $this->expectException(RequestException::class);
        $service->getCurrencyRates(
            new CurrencyEntity('EUR', $amount, new \DateTime()),
            $currencyToRate
        );
    }
}
