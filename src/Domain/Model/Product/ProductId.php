<?php
declare(strict_types=1);

namespace Domain\Model\Product;

use Common\AggregateId;

final class ProductId extends AggregateId
{
    public function equals(ProductId $productId): bool
    {
        return (string)$this === (string)$productId;
    }
}
