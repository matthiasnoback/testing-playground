<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

final class VatRate
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var float
     */
    private $ratePercentage;

    public function __construct(string $code, float $ratePercentage)
    {
        // A float isn't ideal here; I'd rather use a generic "quantity-with-precision" value object here
        $this->code = $code;
        $this->ratePercentage = $ratePercentage;
    }

    public function applyTo(Money $amount): Money
    {
        return $amount->multiply($this->ratePercentage / 100);
    }
}
