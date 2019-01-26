<?php

namespace Domain\Model\SalesInvoice;

use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_multiply_itself_with_a_given_quantity(): void
    {
        $currency = new Currency('EUR');
        $originalAmount = new Money(123, $currency);

        self::assertEquals(new Money(246, $currency), $originalAmount->multiply(2.0));

        // the result would be 258.3, but is rounded down to 258
        self::assertEquals(new Money(258, $currency), $originalAmount->multiply(2.1));

        // the result would be 261.1782, but is rounded up to 261
        self::assertEquals(new Money(261, $currency), $originalAmount->multiply(2.1234));
    }
}
