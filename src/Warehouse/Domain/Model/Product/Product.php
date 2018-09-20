<?php

namespace Warehouse\Domain\Model\Product;

use Common\Aggregate;
use Common\AggregateId;

final class Product extends Aggregate
{
    /**
     * @var ProductId
     */
    private $productId;

    private function __construct(ProductId $productId)
    {
        $this->productId = $productId;
        $this->recordThat(new ProductWasCreated($productId));
    }

    public static function create(ProductId $productId): Product
    {
        return new self($productId);
    }

    public function id(): AggregateId
    {
        return $this->productId;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }
}
