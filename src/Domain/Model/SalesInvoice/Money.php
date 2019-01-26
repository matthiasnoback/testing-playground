<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

use Assert\Assertion;

final class Money
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @var Currency
     */
    private $currency;

    public function __construct(int $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function multiply(float $quantity): Money
    {
        return new Money(
            (int)round($this->amount * $quantity),
            $this->currency
        );
    }

    public function subtract(Money $other): Money
    {
        return new Money(
            $this->amount - $other->amount,
            $this->currency
        );
    }

    public function add(Money $other): Money
    {
        return new Money(
            $this->amount + $other->amount,
            $this->currency
        );
    }

    public function convert(ExchangeRate $exchangeRate): Money
    {
        Assertion::eq($exchangeRate->from(), $this->currency);

        return new Money(
            (int)round($this->amount / $exchangeRate->rate()),
            $exchangeRate->to()
        );
    }

    public function asFloat(): float
    {
        return round(
            $this->amount / (10 ** $this->currency->decimalPrecision()),
            $this->currency->decimalPrecision()
        );
    }
}
