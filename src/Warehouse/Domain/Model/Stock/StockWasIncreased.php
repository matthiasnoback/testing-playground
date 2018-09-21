<?php

namespace Warehouse\Domain\Model\Stock;

use Warehouse\Domain\Model\Product\ProductId;

class StockWasIncreased
{
    private $productId;
    private $quantity;

    public function __construct(ProductId $productId, $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
