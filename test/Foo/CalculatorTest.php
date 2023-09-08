<?php
declare(strict_types=1);

namespace Foo;

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
}
