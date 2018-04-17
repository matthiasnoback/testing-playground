<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

final class PurchaseOrderReopened
{
    /**
     * @var PurchaseOrderId
     */
    private $purchaseOrderId;

    public function __construct(PurchaseOrderId $purchaseOrderId)
    {
        $this->purchaseOrderId = $purchaseOrderId;
    }
}
