<?php
declare(strict_types=1);

use Warehouse\Infrastructure\ServiceContainer;

require __DIR__ . '/../vendor/autoload.php';

$container = new ServiceContainer();

$product = $container->createProductService()->create('Product A');

$purchaseOrder = $container->placePurchaseOrderService()->place([
    (string)$product->productId() => 10
]);

$receiptNote = $container->receiveGoodsService()->receive(
    (string)$purchaseOrder->purchaseOrderId(),
    [
        (string)$product->productId() => 5
    ]
);

$salesOrder = $container->placeSalesOrderService()->place([
    (string)$product->productId() => 7
]);

$deliveryNote = $container->deliverGoodsService()->deliver(
    (string)$salesOrder->salesOrderId(),
    [
        (string)$product->productId() => 3
    ]
);

dump($container->balanceRepository()->findAll());
