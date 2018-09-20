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

    private function __construct(ProductId $productId, string $description)
    {
        $this->productId = $productId;
        $this->recordThat(new ProductWasCreated($productId));
        $this->description = $description;
    }

    public static function create(ProductId $productId, string $description): Product
    {
        return new self($productId, $description);
    }

    public function id(): AggregateId
    {
        return $this->productId;
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
