# PHP developer task

# Project micro-framework -> Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/lumen)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.
The code is easily testable as it mostly uses interfaces, thus it will be easy for us to mock our services and write test cases for our application.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

### Steps to run the application

* step 1: Run command:

```bash
php -S localhost:8087 -t public
```

* step 2: Run command:

```bash
./vendor/phpunit/phpunit/phpunit
```

* step 2: Prepare .env file based on .env.example.
* step 3: Make your first statistics of two repositories.

## GET

``localhost:8080``

**Parameters** [Request Body]


|               Name | Required |  Type  | Description                                                      |
|-------------------:|:--------:|:------:|------------------------------------------------------------------|
| `baseCurrencyCode` | required | string | currency code.                                                   |
|           `amount` | required | float  | Amount you would like to check.                                  |
|       `currencies` | required | array  | array of currencies to compare. It should have 10 items at most. |
|     `currencies.*` | required | string | the `currencies` array should include the array of currency code |

**Example**

```json 
{
    "baseCurrencyCode": "EUR",
    "amount": "10.02",
    "currencies": [
        "USD",
        "PLN"
    ]
}
```
