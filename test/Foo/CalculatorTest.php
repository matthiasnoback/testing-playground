<?php
declare(strict_types=1);

namespace Foo;

use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function test_end_to_end(): void
    {
        $lastLine = exec('php calculator.php wat', $output, $result_code);
        self::assertSame(0, $result_code);
        self::assertEquals('Hello, world wat', $lastLine);
    }
}
