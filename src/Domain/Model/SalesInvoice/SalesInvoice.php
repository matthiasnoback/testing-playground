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
     * @var float|null
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

    public function __construct(Currency $currency, ?float $exchangeRate, int $quantityPrecision)
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

    public function totalNetAmount(): Money
    {
        $sum = new Money(0, $this->currency);

        foreach ($this->lines as $line) {
            $sum = $sum->add($line->netAmount());
        }

        return $sum;
    }

    public function totalNetAmountInLedgerCurrency(): float
    {
        if ($this->currency === 'EUR' || $this->exchangeRate == null) {
            return $this->totalNetAmount();
        }

        return round($this->totalNetAmount()->asFloat() / $this->exchangeRate, 2);
    }

    public function totalVatAmount(): float
    {
        $sum = 0.0;

        foreach ($this->lines as $line) {
            $sum += $line->vatAmount();
        }

        return round($sum, 2);
    }

    public function totalVatAmountInLedgerCurrency(): float
    {
        if ($this->currency === 'EUR' || $this->exchangeRate == null) {
            return $this->totalVatAmount();
        }

        return round($this->totalVatAmount() / $this->exchangeRate, 2);
    }
}
