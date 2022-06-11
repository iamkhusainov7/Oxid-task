<?php

namespace App\Http\Controllers;

use App\DataProviders\Contracts\CurrencyProviderInterface;
use App\Entities\CurrencyEntity;
use App\ResponseFormatters\Contracts\CurrencyResponseHandlerInterface;
use Illuminate\Http\Request;

class CurrencyConverterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private CurrencyProviderInterface $currencyProvider, private CurrencyResponseHandlerInterface $responseHandler)
    {
    }

    public function convert(Request $request)
    {
        $currency = new CurrencyEntity($request->get('baseCurrencyCode', ''), $request->get('amount', .0), new \DateTime());

        $currencies = array_map(fn ($value) => new CurrencyEntity($value, .0, new \DateTime()), $request->get('currencies', []));

        return $this->responseHandler->handle($this->currencyProvider->getCurrencyRates($currency, ...$currencies));
    }
}
