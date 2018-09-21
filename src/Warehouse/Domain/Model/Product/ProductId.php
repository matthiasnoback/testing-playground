<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Product;

use Common\AggregateId;
use Warehouse\Domain\Model\Stock\StockId;

final class ProductId extends AggregateId
{
    public static function fromStockId(StockId $stockId): self
    {
        return self::fromString((string)$stockId);
    }
}
