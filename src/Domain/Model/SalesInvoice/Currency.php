<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

use Assert\Assertion;

final class Currency
{
    /**
     * @var string
     */
    private $currency;

    public function __construct(string $currency)
    {
        Assertion::inArray($currency, ['USD', 'EUR']);
        $this->currency = $currency;
    }

    public function decimalPrecision(): int
    {
        return 2;
    }
}
