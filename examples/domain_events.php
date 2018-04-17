<?php
declare(strict_types=1);

namespace Example;

use Application\ReadModel\BalanceRepository;
use Application\UpdatePurchaseOrder;
use Application\UpdateStockBalance;
use Common\EventDispatcher\EventCliLogger;
use Common\EventDispatcher\EventDispatcher;
use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\PurchaseOrder;
use Domain\Model\PurchaseOrder\PurchaseOrderId;
use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\PurchaseOrder\OrderedQuantity;
use Domain\Model\ReceiptNote\GoodsReceived;
use Domain\Model\ReceiptNote\ReceiptNote;
use Domain\Model\ReceiptNote\ReceiptNoteId;
use Domain\Model\ReceiptNote\ReceiptNoteRepository;
use Domain\Model\ReceiptNote\ReceiptQuantity;
use Domain\Model\ReceiptNote\ReceiptUndone;
use Domain\Model\Supplier\SupplierId;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/../bootstrap.php';

$eventDispatcher = new EventDispatcher();
$eventDispatcher->subscribeToAllEvents(new EventCliLogger());

$balanceRepository = new BalanceRepository($eventDispatcher);
$updateStockBalanceListener = new UpdateStockBalance($balanceRepository);
$eventDispatcher->registerSubscriber(
    GoodsReceived::class,
    [$updateStockBalanceListener, 'whenGoodsReceived']
);
$eventDispatcher->registerSubscriber(
    ReceiptUndone::class,
    [$updateStockBalanceListener, 'whenReceiptUndone']
);

$purchaseOrderRepository = new PurchaseOrderRepository($eventDispatcher);

$updatePurchaseOrderListener = new UpdatePurchaseOrder($purchaseOrderRepository);
$eventDispatcher->registerSubscriber(GoodsReceived::class, [$updatePurchaseOrderListener, 'whenGoodsReceived']);
$eventDispatcher->registerSubscriber(ReceiptUndone::class, [$updatePurchaseOrderListener, 'whenReceiptUndone']);

$receiptNoteRepository = new ReceiptNoteRepository($eventDispatcher);

$product1 = ProductId::fromString(Uuid::uuid4()->toString());
$product2 = ProductId::fromString(Uuid::uuid4()->toString());

$purchaseOrder = PurchaseOrder::create(
    PurchaseOrderId::fromString(Uuid::uuid4()->toString()),
    SupplierId::fromString(Uuid::uuid4()->toString())
);
$purchaseOrder->addLine($product1, new OrderedQuantity(10.0));
$purchaseOrder->addLine($product2, new OrderedQuantity(5.0));
$purchaseOrder->place();

$purchaseOrderRepository->save($purchaseOrder);

$receiptNote1 = ReceiptNote::create(ReceiptNoteId::fromString(Uuid::uuid4()->toString()), $purchaseOrder->purchaseOrderId());
$receiptNote1->receive($product1, new ReceiptQuantity(5.0));
$receiptNote1->receive($product2, new ReceiptQuantity(2.0));

$receiptNoteRepository->save($receiptNote1);

$receiptNote2 = ReceiptNote::create(ReceiptNoteId::fromString(Uuid::uuid4()->toString()), $purchaseOrder->purchaseOrderId());
$receiptNote2->receive($product1, new ReceiptQuantity(5.0));
$receiptNote2->receive($product2, new ReceiptQuantity(3.0));
$receiptNoteRepository->save($receiptNote2);

$receiptNote2->undo();
$receiptNoteRepository->save($receiptNote2);

