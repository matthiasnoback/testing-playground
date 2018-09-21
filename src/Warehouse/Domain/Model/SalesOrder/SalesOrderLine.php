<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use Warehouse\Domain\Model\Product\ProductId;

final class SalesOrderLine
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var int
     */
    private $quantity;

    private $isReserved = false;

    public function __construct(ProductId $productId, int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function markReserved(): void
    {
        $this->isReserved = true;
    }

    public function isReserved(): bool
    {
        return $this->isReserved;
    }

    public function markReleased(): void
    {
        $this->isReserved = false;
    }
}
