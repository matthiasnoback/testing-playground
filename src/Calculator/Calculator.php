<?php

namespace Calculator;

final class Calculator
{
    public function calculate(int $quantity, float $amount): float
    {
        $totalPrice = $quantity * $amount;

        $discount = $this->getDiscount($totalPrice);

        return $totalPrice * (100 - $discount) / 100;
    }

    public function getDiscount(float $totalPrice): int
    {
        if ($totalPrice >= 200) {
            return 10;
        }

        if ($totalPrice >= 100) {
            return 5;
        }

        return 0;
    }
}
