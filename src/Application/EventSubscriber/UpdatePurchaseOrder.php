<?php
declare(strict_types=1);

namespace Application\EventSubscriber;

use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\ReceiptNote\GoodsReceived;
use Domain\Model\ReceiptNote\ReceiptUndone;
use Infrastructure\ServiceContainer;

final class UpdatePurchaseOrder
{
    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function whenGoodsReceived(GoodsReceived $goodsReceived): void
    {
        $purchaseOrder = $this->purchaseOrderRepository()->getById($goodsReceived->purchaseOrderId());

        $purchaseOrder->processReceipt($goodsReceived->productId(), $goodsReceived->quantity());

        $this->purchaseOrderRepository()->save($purchaseOrder);
    }

    public function whenReceiptUndone(ReceiptUndone $receiptUndone): void
    {
        $purchaseOrder = $this->purchaseOrderRepository()->getById($receiptUndone->purchaseOrderId());

        $purchaseOrder->undoReceipt($receiptUndone->productId(), $receiptUndone->quantity());

        $this->purchaseOrderRepository()->save($purchaseOrder);
    }

    private function purchaseOrderRepository(): PurchaseOrderRepository
    {
        return $this->serviceContainer->purchaseOrderRepository();
    }
}
