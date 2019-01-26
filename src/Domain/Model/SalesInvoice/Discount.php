<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

use Assert\Assertion;

final class Discount
{
    /**
     * @var float
     */
    private $percentage;

    public static function fromPercentage(float $discountPercentage): Discount
    {
        Assertion::greaterOrEqualThan($discountPercentage, 0);
        $discount = new self();

        $discount->percentage = $discountPercentage;

        return $discount;
    }

    public static function none(): Discount
    {
        // Previously the "null" case. No discount, is the same as 0% discount.
        return self::fromPercentage(0.0);
    }

    public function discountAmountFor(Money $amount): Money
    {
        return $amount->multiply(($this->percentage / 100));
    }
}
