<?php
declare(strict_types=1);

use Warehouse\Infrastructure\ServiceContainer;

require __DIR__ . '/bootstrap.php';

$serviceContainer = new ServiceContainer();

$productId = $serviceContainer->createProductService()->create('Mars Rover');
dump($serviceContainer->productRepository()->getById($productId));

$purchaseOrderId = $serviceContainer->createPurchaseOrderService()->place(
    [
        (string)$productId => 10
    ]);
dump($serviceContainer->purchaseOrderRepository()->getById($purchaseOrderId));

$receiptNoteId = $serviceContainer->receiveGoods()->receive(
    (string)$purchaseOrderId,
    [
        (string)$productId => 10
    ]
);
dump($serviceContainer->receiptNoteRepository()->getById($receiptNoteId));

$salesOrderId = $serviceContainer->placeSalesOrderService()->place(
    [
        (string)$productId => 4
    ]
);
dump($serviceContainer->salesOrderRepository()->getById($salesOrderId));

$deliverNoteId = $serviceContainer->deliverGoodsService()->deliver(
    (string)$salesOrderId
);
dump($deliverNoteId);
