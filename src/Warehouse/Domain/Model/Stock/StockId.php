<?php

namespace Warehouse\Domain\Model\Stock;

use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;

class StockId extends AggregateId
{
    public static function fromProductId(ProductId $productId): StockId
    {
        return self::fromString((string) $productId);
    }
}
