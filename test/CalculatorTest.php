<?php

use PHPUnit\Framework\TestCase;
use Services\Calculator;

class CalculatorTest extends TestCase
{
    public function testPricePerUnitEndToEnd(): void
    {
        // Given the price per unit is 25 cents
        // And the quantity is 10
        // Then the total price is 250 cents
        $output = [];
        exec('php bin/console.php 10 25', $output);
        self::assertSame(['250'], $output);
    }

    public function testPricePerUnit(): void
    {
        // Given the price per unit is 25 cents
        // And the quantity is 10
        // Then the total price is 250 cents
        self::assertSame(250, $this->calculator()->calculate(10, 25));
    }

    public function testDiscount(): void
    {
        // Given the price per unit is 25 cents
        // And the quantity is 10
        // When we apply 5% discount
        // Then the total price is 238 cents
        self::assertSame(238, $this->calculator()->calculate(10, 25, 5));
    }

    public function testTax(): void
    {
        self::assertSame(285, $this->calculator()->calculate(10, 25, 5, 20));
    }

    /**
     * @return Calculator
     */
    public function calculator(): Calculator
    {
        return new Calculator();
    }
}
