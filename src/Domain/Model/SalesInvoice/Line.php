<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

use DateTime;
use InvalidArgumentException;

final class Line
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var int
     */
    private $quantityPrecision;

    /**
     * @var float
     */
    private $tariff;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var Discount
     */
    private $discount;

    /**
     * @var VatRate
     */
    private $vatRate;

    /**
     * @var float|null
     */
    private $exchangeRate;

    public function __construct(
        string $description,
        float $quantity,
        int $quantityPrecision,
        float $tariff,
        Currency $currency,
        Discount $discount,
        VatRate $vatRate,
        ?float $exchangeRate
    ) {
        $this->description = $description;
        $this->quantity = $quantity;
        $this->quantityPrecision = $quantityPrecision;
        $this->tariff = $tariff;
        $this->currency = $currency;
        $this->discount = $discount;
        $this->vatRate = $vatRate;
        $this->exchangeRate = $exchangeRate;
    }

    public function amount(): float
    {
        return round(round($this->quantity, $this->quantityPrecision) * $this->tariff, 2);
    }

    public function discountAmount(): float
    {
        return $this->discount->discountAmountFor($this->amount());
    }

    public function netAmount(): float
    {
        return round($this->amount() - $this->discountAmount(), 2);
    }

    public function vatAmount(): float
    {
        return $this->vatRate->applyTo($this->netAmount());
    }

    public function netAmountInLedgerCurrency(): float
    {
        if ($this->currency == new Currency('EUR') || $this->exchangeRate === null) {
            return $this->netAmount();
        }

        return round($this->netAmount() / $this->exchangeRate, 2);
    }

    public function vatAmountInLedgerCurrency(): float
    {
        if ($this->currency == new Currency('EUR') || $this->exchangeRate === null) {
            return $this->vatAmount();
        }

        return round($this->vatAmount() / $this->exchangeRate, 2);
    }
}
