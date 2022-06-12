<?php

namespace App\Exceptions;

use GuzzleHttp\Exception\BadResponseException;

class CurrencyNotFoundException extends BadResponseException
{
}
