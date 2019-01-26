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
        // We could get rid of $currency here, because it's enclosed in the ExchangeRate value object
        $this->currency = $currency;
        $this->exchangeRate = $exchangeRate;
        // We should get rid of quantity precision, and instead use a generic quantity-with-precision value object
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
        // We could have a sum function on Money, but then we would need to provide it with all the Money objects,
        // which would require the use of array_map or something like that. I decided to keep it simple like this.

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
        // Something that's not really clear anymore is - what is "ledger currency"?
        // Still, this is an improvement, because we use a "Null object"-like pattern so we can always convert.

        return $this->totalVatAmount()->convert($this->exchangeRate);
    }
}
