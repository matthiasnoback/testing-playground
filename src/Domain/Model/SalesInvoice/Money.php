<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

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

    public function asFloat(): float
    {
        return round(
            $this->amount / (10 ** $this->currency->decimalPrecision()),
            $this->currency->decimalPrecision()
        );
    }
}
