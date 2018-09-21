<?php

namespace Warehouse\Domain\Model\Product\Balance;

use Warehouse\Domain\Model\Product\ProductId;

class StockWasDecreased
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
