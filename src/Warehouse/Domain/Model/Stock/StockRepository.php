<?php

namespace Warehouse\Domain\Model\Stock;

use Warehouse\Domain\Model\Product\ProductId;

interface StockRepository
{
    public function save(Stock $aggregate): void;

    public function getByProductId(ProductId $productId): Stock;
}
