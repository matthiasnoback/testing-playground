<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

final class PurchaseOrderCompleted
{
    /**
     * @var PurchaseOrderId
     */
    private $purchaseOrderId;

    /**
     * @param PurchaseOrderId $purchaseOrderId
     */
    public function __construct(PurchaseOrderId $purchaseOrderId)
    {
        $this->purchaseOrderId = $purchaseOrderId;
    }
}
