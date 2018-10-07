<?php
declare(strict_types=1);

use Warehouse\Infrastructure\ServiceContainer;

require __DIR__ . '/bootstrap.php';

$serviceContainer = new ServiceContainer();

$product = $serviceContainer->createProductService()->create('Mars Rover');
dump($product);

$purchaseOrder = $serviceContainer->placePurchaseOrderService()->place([
    (string)$product->productId() => 10
]);
dump($purchaseOrder);

$salesOrder = $serviceContainer->placeSalesOrderService()->place([
    (string)$product->productId() => 4
]);
dump($salesOrder);
