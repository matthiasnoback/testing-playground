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
     * @var ExchangeRate
     */
    private $exchangeRate;

    public function __construct(
        string $description,
        float $quantity,
        int $quantityPrecision,
        Money $tariff,
        Currency $currency,
        Discount $discount,
        VatRate $vatRate,
        ExchangeRate $exchangeRate
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

    public function amount(): Money
    {
        return $this->tariff->multiply(round($this->quantity, $this->quantityPrecision));
    }

    public function discountAmount(): Money
    {
        return $this->discount->discountAmountFor($this->amount());
    }

    public function netAmount(): Money
    {
        return $this->amount()->subtract($this->discountAmount());
    }

    public function vatAmount(): Money
    {
        return $this->vatRate->applyTo($this->netAmount());
    }

    public function netAmountInLedgerCurrency(): Money
    {
        return $this->netAmount()->convert($this->exchangeRate);
    }

    public function vatAmountInLedgerCurrency(): float
    {
        return $this->vatAmount()->convert($this->exchangeRate);
    }
}
