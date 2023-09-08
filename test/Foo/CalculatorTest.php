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
        self::assertStringContainsString('10', $lastLine);
    }

    public function test_multiply(): void
    {
        $quantity = random_int(1, 100);
        $amount = (float) (random_int(1, 100) . '.' . random_int(0, 99));
        $lastLine = exec('php calculator.php ' . $quantity . ' ' . $amount, $output);
        $result = round($quantity * $amount, 2);

        self::assertEquals($result, $lastLine);
    }
}
