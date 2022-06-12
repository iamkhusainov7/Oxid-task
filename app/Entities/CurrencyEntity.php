<?php

namespace App\Entities;

use DateTimeInterface;
use Illuminate\Support\Collection;

class CurrencyEntity
{
    /**
     * @param string                               $code
     * @param float                                $value
     * @param DateTimeInterface                    $date
     * @param Collection<int, CurrencyEntity>|null $currenciesRates
     */
    public function __construct(
        protected string $code,
        protected float $value,
        protected DateTimeInterface $date,
        protected ?Collection $currenciesRates = null
    ) {
    }

    public function __toString(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return Collection<int, CurrencyEntity>|null
     */
    public function getCurrenciesRates(): ?Collection
    {
        return $this->currenciesRates;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param Collection|null $comparisons
     *
     * @return CurrencyEntity
     */
    public function setComparisons(?Collection $comparisons): self
    {
        $this->currenciesRates = $comparisons;

        return $this;
    }

    /**
     * @param float $value
     *
     * @return CurrencyEntity
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        $this->currenciesRates?->filter(
            fn (CurrencyEntity $entity) => $entity !== $this
        )->each(
            fn (CurrencyEntity $entity) => $entity->setValue($value * $entity->getValue())
        );

        return $this;
    }
}
