<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\PurchaseOrder;

use Common\Aggregate;
use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;

final class PurchaseOrder extends Aggregate
{
    /**
     * @var PurchaseOrderId
     */
    private $purchaseOrderId;

    /**
     * @var array|PurchaseOrderLine[]
     */
    private $lines = [];

    private function __construct()
    {
    }

    public static function create(PurchaseOrderId $purchaseOrderId): PurchaseOrder
    {
        $purchaseOrder = new self();

        $purchaseOrder->purchaseOrderId = $purchaseOrderId;

        return $purchaseOrder;
    }

    public function id(): AggregateId
    {
        return $this->purchaseOrderId;
    }

    public function addLine(ProductId $productId, int $quantity): void
    {
        $this->lines[] = new PurchaseOrderLine($productId, $quantity);
    }

    /**
     * @return array|PurchaseOrderLine[]
     */
    public function lines(): array
    {
        return $this->lines;
    }
}
