<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

final class ExchangeRate
{
    /**
     * @var Currency
     */
    private $from;

    /**
     * @var Currency
     */
    private $to;

    /**
     * @var float
     */
    private $rate;

    public function __construct(Currency $from, Currency $to, float $rate)
    {
        $this->from = $from;
        $this->to = $to;
        $this->rate = $rate;
    }

    public function from(): Currency
    {
        return $this->from;
    }

    public function to(): Currency
    {
        return $this->to;
    }

    public function rate(): float
    {
        return $this->rate;
    }

    public static function noExchangeRate(Currency $currency): ExchangeRate
    {
        return new self($currency, $currency, 1.0);
    }
}
