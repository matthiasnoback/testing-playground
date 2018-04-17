<?php
declare(strict_types=1);

namespace Application;

use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\ReceiptNote\GoodsReceived;

final class UpdatePurchaseOrder
{
    /**
     * @var PurchaseOrderRepository
     */
    private $purchaseOrderRepository;

    public function __construct(PurchaseOrderRepository $purchaseOrderRepository)
    {
        $this->purchaseOrderRepository = $purchaseOrderRepository;
    }

    public function whenGoodsReceived(GoodsReceived $goodsReceived): void
    {
        $purchaseOrder = $this->purchaseOrderRepository->getById($goodsReceived->purchaseOrderId());

        $purchaseOrder->processReceipt($goodsReceived->productId(), $goodsReceived->quantity());

        $this->purchaseOrderRepository->save($purchaseOrder);
    }
}
