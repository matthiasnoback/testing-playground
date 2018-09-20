<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Common\EventDispatcher\EventDispatcher;
use Warehouse\Application\ReceiveGoodsService;
use Warehouse\Domain\Model\Product\Product;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrder;
use Warehouse\Infrastructure\ProductAggregateRepository;
use Warehouse\Infrastructure\PurchaseOrderAggregateRepository;
use Warehouse\Infrastructure\ReceiptNoteAggregateRepository;

$eventDispatcher = new EventDispatcher();
$productRepository = new ProductAggregateRepository($eventDispatcher);
$purchaseOrderRepository = new PurchaseOrderAggregateRepository($eventDispatcher);
$receiptNoteRepository = new ReceiptNoteAggregateRepository($eventDispatcher);

$product1 = Product::create($productRepository->nextIdentity());
$productRepository->save($product1);

$purchaseOrder = PurchaseOrder::create(
    $purchaseOrderRepository->nextIdentity()
);
$purchaseOrder->addLine($product1->productId(), 10);
$purchaseOrderRepository->save($purchaseOrder);

$service = new ReceiveGoodsService($purchaseOrderRepository, $receiptNoteRepository);
$receiptNote = $service->receive(
    (string)$purchaseOrder->purchaseOrderId(),
    [
        (string)$product1->productId() => 5
    ]
);

dump($receiptNote);
