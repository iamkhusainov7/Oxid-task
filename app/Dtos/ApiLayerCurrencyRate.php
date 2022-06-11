<?php

namespace App\Dtos;

class ApiLayerCurrencyRate extends DtoTemplate
{
    public string $base = '';
    public string $date = '';
    public array $rates = [];
}
