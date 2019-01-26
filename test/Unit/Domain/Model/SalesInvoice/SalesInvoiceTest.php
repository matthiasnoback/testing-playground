<?php

namespace Domain\Model\SalesInvoice;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SalesInvoiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_calculates_the_correct_totals_for_an_invoice_in_foreign_currency(): void
    {
        $currency = new Currency('USD');
        $salesInvoice = new SalesInvoice($currency, 1.3, 3);
        $salesInvoice->addLine(
            'Product with a 10% discount and standard VAT applied',
            2.0,
            new Money(1500, $currency),
            Discount::fromPercentage(10.0),
            new VatRate('S', 21.0)
        );
        $salesInvoice->addLine(
            'Product with no discount and low VAT applied',
            3.123456,
            new Money(1250, $currency),
            Discount::none(),
            new VatRate('L', 9.0)
        );

        /*
         * 2 * 15.00 - 10% = 27.00
         * +
         * 3.123 * 12.50 - 0% = 39.04
         * =
         * 66.04
         */
        self::assertEquals(66.04, $salesInvoice->totalNetAmount());

        /*
         * 66.04 / 1.3 = 50.80
         */
        self::assertEquals(50.80, $salesInvoice->totalNetAmountInLedgerCurrency());

        /*
         * 27.00 * 21% = 5.67
         * +
         * 39.04 * 9% = 3.51
         * =
         * 9.18
         */
        self::assertEquals(9.18, $salesInvoice->totalVatAmount());

        /*
         * 9.18 / 1.3 = 7.06
         */
        self::assertEquals(7.06, $salesInvoice->totalVatAmountInLedgerCurrency());
    }

    /**
     * @test
     */
    public function it_calculates_the_correct_totals_for_an_invoice_in_ledger_currency(): void
    {
        $currency = new Currency('EUR');
        $salesInvoice = new SalesInvoice($currency, 1, 3);
        $salesInvoice->addLine(
            'Product with a 10% discount and standard VAT applied',
            2.0,
            new Money(1500, $currency),
            Discount::fromPercentage(10.0),
            new VatRate('S', 21.0)
        );
        $salesInvoice->addLine(
            'Product with no discount and low VAT applied',
            3.123456,
            new Money(1250, $currency),
            Discount::none(),
            new VatRate('L', 9.0)
        );

        self::assertEquals($salesInvoice->totalNetAmount(), $salesInvoice->totalNetAmountInLedgerCurrency());
        self::assertEquals($salesInvoice->totalVatAmount(), $salesInvoice->totalVatAmountInLedgerCurrency());
    }
}
