<?php

use Services\Calculator;

require __DIR__ . '/../vendor/autoload.php';

$quantity = (int) $argv[1];
$pricePerUnit = (int) $argv[2];
$discount = (int) ($argv[3] ?? null);
$tax = (int) ($argv[4] ?? 0);

$calculator = new Calculator();
$totalPrice = $calculator->calculate($quantity, $pricePerUnit, $discount, $tax);

echo $totalPrice;
