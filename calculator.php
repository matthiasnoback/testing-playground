<?php
declare(strict_types=1);

$quantity = (int) $argv[1];
// is het wel een geheel getal? zo niet, exception
$amount = round((float) str_replace(',', '.', $argv[2]), 2);
$result = round($quantity * $amount, 2);

echo number_format($result, 2, ',');
