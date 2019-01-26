<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

use Assert\Assertion;

final class SalesInvoice
{
    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var ExchangeRate
     */
    private $exchangeRate;

    /**
     * @var int
     */
    private $quantityPrecision;

    /**
     * @var Line[]
     */
    private $lines = [];

    public function __construct(Currency $currency, ExchangeRate $exchangeRate, int $quantityPrecision)
    {
        $this->currency = $currency;
        $this->exchangeRate = $exchangeRate;
        $this->quantityPrecision = $quantityPrecision;
    }

    public function addLine(
        string $description,
        float $quantity,
        Money $tariff,
        Discount $discount,
        VatRate $vatRate
    ): void {
        $this->lines[] = new Line(
            $description,
            $quantity,
            $this->quantityPrecision,
            $tariff,
            $this->currency,
            $discount,
            $vatRate,
            $this->exchangeRate
        );
    }

    public function totalNetAmountInLedgerCurrency(): Money
    {
        return $this->totalNetAmount()->convert($this->exchangeRate);
    }

    public function totalNetAmount(): Money
    {
        $sum = new Money(0, $this->currency);

        foreach ($this->lines as $line) {
            $sum = $sum->add($line->netAmount());
        }

        return $sum;
    }

    public function totalVatAmount(): Money
    {
        $sum = new Money(0, $this->currency);

        foreach ($this->lines as $line) {
            $sum = $sum->add($line->vatAmount());
        }

        return $sum;
    }

    public function totalVatAmountInLedgerCurrency(): Money
    {
        return $this->totalVatAmount()->convert($this->exchangeRate);
    }
}
