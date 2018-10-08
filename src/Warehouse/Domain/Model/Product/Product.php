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

    /**
     * @var string
     */
    private $description;

    private function __construct()
    {
    }

    public function id(): AggregateId
    {
        return $this->productId;
    }

    public static function create(ProductId $productId, string $description): Product
    {
        $product = new self();

        $product->productId = $productId;
        $product->description = $description;

        $product->recordThat(new ProductCreated($productId, $description));

        return $product;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function description(): string
    {
        return $this->description;
    }
}
