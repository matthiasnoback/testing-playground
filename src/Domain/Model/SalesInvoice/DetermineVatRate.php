<?php
declare(strict_types=1);

namespace Domain\Model\SalesInvoice;

use DateTime;

// This could be a service you can use to determine the VAT code and rate
final class DetermineVatRate
{
    public function lowRateForDate(DateTime $date)
    {
        if ($date < DateTime::createFromFormat('Y-m-d', '2019-01-01')) {
            return new VatRate('L', 6.0);
        }

        return new VatRate('L', 9.0);
    }

    public function standardRateForDate(DateTime $date)
    {
        return new VatRate('S', 21.0);
    }
}
