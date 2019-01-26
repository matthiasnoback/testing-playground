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
        $this->code = $code;
        $this->ratePercentage = $ratePercentage;
    }

    public function applyTo(Money $amount): Money
    {
        return $amount->multiply($this->ratePercentage / 100);
    }
}
