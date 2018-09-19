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

    private function __construct()
    {
    }

    public function id(): AggregateId
    {
        return $this->productId;
    }

    public static function create(ProductId $productId): Product
    {
        $product = new self();

        $product->productId = $productId;

        return $product;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }
}
