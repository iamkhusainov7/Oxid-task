<?php

namespace Tests\ResponseFormatters;

use App\Dtos\CurrencyRateDto;
use App\Entities\CurrencyEntity;
use App\ResponseFormatters\JsonCurrencyResponseFormatter;
use Tests\TestCase;

class JsonCurrencyResponseFormatterTest extends TestCase
{
    public function testHandle()
    {
        $formatter = new JsonCurrencyResponseFormatter();
        $currency = new CurrencyEntity('USD', 10.0, new \DateTime());
        $response = $formatter->format($currency);

        $this->assertTrue($response->getStatusCode() === 200);
        $this->assertTrue($response->getContent() === json_encode(CurrencyRateDto::fromEntity($currency)));
    }
}
