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
        $totalPrice = $quantity * $pricePerUnit;
        $totalPrice = $this->applyDiscountPercentage($totalPrice, $discount);
        $totalPrice = $this->applyTaxPercentage($totalPrice, $tax);

        return (int) round($totalPrice);
    }

    private function applyDiscountPercentage(float|int $totalPrice, int $discount): int|float
    {
        return $this->applyPercentage($totalPrice, -1 * $discount);
    }

    private function applyTaxPercentage(float|int $totalPrice, int $tax): int|float
    {
        return $this->applyPercentage($totalPrice, $tax);
    }

    private function applyPercentage(float|int $totalPrice, int $discount): int|float
    {
        return $totalPrice * $this->asFactor($discount);
    }

    private function asFactor(int $percentage): int|float
    {
        return (100 + $percentage) / 100;
    }
}
