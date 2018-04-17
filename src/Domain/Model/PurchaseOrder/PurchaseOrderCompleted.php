<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use function Common\CommandLine\line;
use function Common\CommandLine\make_green;

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

    public function __toString()
    {
        return line(
            make_green('Purchase order completed'),
            sprintf(': %s', $this->purchaseOrderId)
        );
    }
}
