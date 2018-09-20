<?php

namespace Warehouse\Domain\Model\ReceiptNote;

use Warehouse\Domain\Model\Product\ProductId;

class GoodsReceived
{
    private $productId;
    private $quantity;

    public function __construct(ProductId $productId, int $quantity)
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
