<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\PurchaseOrder;

use Assert\Assertion;
use Common\Aggregate;
use Common\AggregateId;
use LogicException;
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

    /**
     * @var bool
     */
    private $wasPlaced = false;

    private function __construct()
    {
    }

    public static function create(PurchaseOrderId $purchaseOrderId): PurchaseOrder
    {
        $purchaseOrder = new self();

        $purchaseOrder->purchaseOrderId = $purchaseOrderId;

        $purchaseOrder->recordThat(new PurchaseOrderCreated($purchaseOrderId));

        return $purchaseOrder;
    }

    public function id(): AggregateId
    {
        return $this->purchaseOrderId;
    }

    public function purchaseOrderId(): PurchaseOrderId
    {
        return $this->purchaseOrderId;
    }

    public function addLine(ProductId $productId, int $quantity): void
    {
        Assertion::false($this->wasPlaced, 'You cannot add lines to an already placed purchase order.');

        $this->lines[] = new PurchaseOrderLine($productId, $quantity);
    }

    public function place(): void
    {
        if (\count($this->lines) === 0) {
            throw new LogicException('A purchase order should have at least one line');
        }

        $this->wasPlaced = true;
    }

    /**
     * @return array|PurchaseOrderLine[]
     */
    public function lines(): array
    {
        return $this->lines;
    }
}
