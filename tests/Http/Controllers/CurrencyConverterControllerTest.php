<?php

namespace Tests\Http\Controllers;

use App\DataProviders\ApiLayerCurrencyProvider;
use App\Dtos\CurrencyRateDto;
use App\Entities\CurrencyEntity;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class CurrencyConverterControllerTest extends TestCase
{
    public function testConvert()
    {
        $responseStructure = [
            "success"   => true,
            "timestamp" => 1654984204,
            "base"      => "EUR",
            "date"      => "2022-06-11",
            "rates"     => [
                "USD" => 3.8,
                "PLN" => 94.0,
            ],
        ];
        $response          = new Response(body: json_encode($responseStructure));
        $client            = $this->createMock(ClientInterface::class);
        $client->method('request')
            ->willReturn($response);

        $this->app->when(ApiLayerCurrencyProvider::class)
            ->needs(ClientInterface::class)
            ->give(fn () => $client);

        $server = $this->transformHeadersToServerVars([]);
        $data   = [
            "baseCurrencyCode" => "EUR",
            "amount"           => 10.00,
            "currencies"       => [
                "USD",
                "PLN",
            ],
        ];

        $this->call('GET', '/', $data, [], [], $server);
        $baseCurrency = new CurrencyEntity($data['baseCurrencyCode'], $data['amount'], new \DateTime());
        $currencies = array_map(fn ($value) => new CurrencyEntity($value, $value !== 'USD' ? 940 : 38, new \DateTime()), $data['currencies']);
        $baseCurrency->setComparisons(collect($currencies));

        $this->assertEquals(
           json_encode(CurrencyRateDto::fromEntity($baseCurrency)) , $this->response->getContent()
        );
    }
}
