<?php
declare(strict_types=1);

namespace Foo;

use Calculator\Calculator;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function test_end_to_end(): void
    {
        $lastLine = exec('php calculator.php 2 5', $output, $result_code);
        self::assertSame(0, $result_code, implode("\n", $output));
        self::assertIsString($lastLine);
        self::assertStringContainsString('10', $lastLine);
    }

    public function test_rounded_multiply(): void
    {
        $lastLine = exec('php calculator.php 5 10');

        self::assertEquals('50,00', $lastLine);
    }

    public function test_input_has_decimals(): void
    {
        $lastLine = exec('php calculator.php 5 10,10');

        self::assertEquals('50,50', $lastLine);
    }

    public function test_amount_is_100(): void
    {
        $this->assertEquals(95.0, (new Calculator())->calculate(10, 10.0));
    }

    public function test_get_discount(): void
    {
        $this->assertEquals(0, (new Calculator())->getDiscount(95));
        $this->assertEquals(5, (new Calculator())->getDiscount(100));
        $this->assertEquals(5, (new Calculator())->getDiscount(150));
        $this->assertEquals(10, (new Calculator())->getDiscount(200));
        $this->assertEquals(10, (new Calculator())->getDiscount(300));
    }
}
