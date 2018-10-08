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

$receiptNote = $serviceContainer->receiveGoods()->receive(
    (string) $purchaseOrder->purchaseOrderId(), [
        (string) $product->productId() => 10
    ]
);
dump($receiptNote);

$deliverNote = $serviceContainer->deliverGoods()->deliver(
    (string) $salesOrder->salesOrderId(), [
        (string) $product->productId() => 4
    ]
);
dump($deliverNote);

