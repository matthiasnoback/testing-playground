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

    /** @var bool */
    private $isDeliverable = false;

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

    public function isDeliverable(): bool
    {
        return $this->isDeliverable;
    }

    public function markAsDeliverable(): void
    {
        $this->isDeliverable = true;
    }
}
