<?php

namespace App\DataProviders;

use App\DataProviders\Contracts\CurrencyProviderInterface;
use App\DataProviders\Templates\ApiDataProvider;
use App\Dtos\ApiLayerCurrencyRate;
use App\Entities\CurrencyEntity;
use DateTime;

class ApiLayerCurrencyProvider extends ApiDataProvider implements CurrencyProviderInterface
{
    protected const API_URL               = 'https://api.apilayer.com/exchangerates_data/';
    public const    LATEST_RATES_ENDPOINT = 'latest?symbols={symbols}&base={base}';

    public function getCurrencyRates(CurrencyEntity $baseCurrency, CurrencyEntity ...$currencies): CurrencyEntity
    {
        $currencies = implode(',', $currencies); //join currencies according to api requirements

        $response = $this->get(self::API_URL . self::LATEST_RATES_ENDPOINT, collect([
            'base'    => $baseCurrency,
            'symbols' => $currencies,
        ]));

        $bodyDecoded = json_decode($response->getBody(), true) ?: [];

        $dto = new ApiLayerCurrencyRate($bodyDecoded);

        $rates = collect();

        foreach ($dto->rates as $currency => $value) {
            $rates->push(new CurrencyEntity(
                $currency,
                $value * $baseCurrency->getValue(),
                new DateTime($dto->date)
            ),
            );
        }

        return $baseCurrency->setComparisons($rates);
    }
}
