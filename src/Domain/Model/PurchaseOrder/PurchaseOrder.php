<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\Product\Product;
use Domain\Model\Supplier\Supplier;
use Domain\Model\Supplier\SupplierId;
use InvalidArgumentException;
use LogicException;

final class PurchaseOrder
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

    private function __construct(PurchaseOrderId $purchaseOrderId, Supplier $supplier)
    {
        $this->supplierId = $supplier->supplierId();
        $this->purchaseOrderId = $purchaseOrderId;
    }

    public static function create(PurchaseOrderId $purchaseOrderId, Supplier $supplier): PurchaseOrder
    {
        return new self($purchaseOrderId, $supplier);
    }

    public function addLine(Product $product, float $quantity): void
    {
        foreach ($this->lines as $line) {
            if ($line->productId()->equals($product->productId())) {
                throw new InvalidArgumentException('You cannot add the same product twice.');
            }
        }

        $this->lines[] = new Line($product, $quantity);
    }

    public function place(): void
    {
        if (\count($this->lines) < 1) {
            throw new LogicException('To place a purchase order, it has to have at least one line.');
        }
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
