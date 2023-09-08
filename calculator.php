<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Calculator\Calculator;

$quantity = (int) $argv[1];
// is het wel een geheel getal? zo niet, exception
$amount = round((float) str_replace(',', '.', $argv[2]), 2);

$result = (new Calculator())->calculate($quantity, $amount);

echo number_format($result, 2, ',');
