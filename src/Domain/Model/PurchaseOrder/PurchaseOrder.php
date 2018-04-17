<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Common\Aggregate;
use Common\AggregateId;
use Domain\Model\Product\ProductId;
use Domain\Model\Supplier\Supplier;
use Domain\Model\Supplier\SupplierId;
use InvalidArgumentException;
use LogicException;

final class PurchaseOrder extends Aggregate
{
    /**
     * @var SupplierId
     */
    private $supplierId;

    /**
     * @var Line[]
     */
    private $lines = [];
    /**
     * @var PurchaseOrderId
     */
    private $purchaseOrderId;

    /**
     * @var bool
     */
    private $placed = false;

    private function __construct(PurchaseOrderId $purchaseOrderId, Supplier $supplier)
    {
        $this->supplierId = $supplier->supplierId();
        $this->purchaseOrderId = $purchaseOrderId;
    }

    public static function create(PurchaseOrderId $purchaseOrderId, Supplier $supplier): PurchaseOrder
    {
        return new self($purchaseOrderId, $supplier);
    }

    public function addLine(ProductId $productId, Quantity $quantity): void
    {
        foreach ($this->lines as $line) {
            if ($line->productId()->equals($productId)) {
                throw new InvalidArgumentException('You cannot add the same product twice.');
            }
        }

        $this->lines[] = new Line($productId, $quantity);
    }

    public function place(): void
    {
        if ($this->placed) {
            throw new LogicException('This purchase order has already been placed.');
        }

        if (\count($this->lines) < 1) {
            throw new LogicException('To place a purchase order, it has to have at least one line.');
        }

        $this->placed = true;

        $this->recordThat(new PurchaseOrderPlaced($this->purchaseOrderId));
    }

    public function id(): AggregateId
    {
        return $this->purchaseOrderId;
    }

    public function supplierId(): SupplierId
    {
        return $this->supplierId;
    }

    /**
     * @return Line[]
     */
    public function lines(): array
    {
        return $this->lines;
    }
}
