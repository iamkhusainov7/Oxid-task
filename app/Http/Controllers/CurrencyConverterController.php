<?php

namespace App\Http\Controllers;

use App\DataProviders\Contracts\CurrencyProviderInterface;
use App\Entities\CurrencyEntity;
use App\ResponseFormatters\Contracts\CurrencyResponseHandlerInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function convert(Request $request): Response
    {
        $this->validate($request, [
            'baseCurrencyCode' => 'required|string',
            'amount' => 'required|numeric',
            'currencies' => 'required|array|max:10',
            'currencies.*' => 'string|distinct',
        ]);

        $currency = new CurrencyEntity($request->get('baseCurrencyCode', ''), $request->get('amount', .0), new \DateTime());

        $currencies = array_map(fn ($value) => new CurrencyEntity($value, .0, new \DateTime()), $request->get('currencies', []));

        return $this->responseHandler->format($this->currencyProvider->getCurrencyRates($currency, ...$currencies));
    }
}
