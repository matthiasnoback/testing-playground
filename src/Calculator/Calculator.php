<?php

namespace Calculator;

final class Calculator
{
    public function calculate(int $quantity, float $amount): float
    {
        return $quantity * $amount;
    }
}
