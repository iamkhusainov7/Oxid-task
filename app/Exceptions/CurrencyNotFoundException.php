<?php

namespace App\Exceptions;

use App\Entities\CurrencyEntity;
use GuzzleHttp\Exception\BadResponseException;

class CurrencyNotFoundException extends BadResponseException
{
}
