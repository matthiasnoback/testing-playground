<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use function Common\CommandLine\line;
use function Common\CommandLine\make_red;

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

    public function __toString()
    {
        return line(
            make_red('Purchase order reopened'),
            sprintf(': %s', $this->purchaseOrderId)
        );
    }
}
