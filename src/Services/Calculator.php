<?php
declare(strict_types=1);

namespace Services;

class Calculator
{
    public function calculate(
        int $quantity,
        int $pricePerUnit,
        int $discount = 0,
        int $tax = 0
    ): int
    {
        $aVariable = 1;
        if ($discount > 0) {
            $aVariable = (100 - $discount) / 100;
        }
        $totalPrice = $quantity * $pricePerUnit * $aVariable;
        $totalPrice *= (100 + $tax) / 100;

        return (int) round($totalPrice);
    }
}
