<?php
declare(strict_types=1);

namespace Domain\Model\Product;

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
    private $name;

    /**
     * @var bool
     */
    private $isStockProduct;

    /**
     * @var bool
     */
    private $useBatchNumbers;

    public function __construct(ProductId $productId, string $name, bool $isStockProduct, bool $useBatchNumbers)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->isStockProduct = $isStockProduct;
        $this->useBatchNumbers = $useBatchNumbers;
    }

    public function id(): AggregateId
    {
        return $this->productId;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function isStockProduct(): bool
    {
        return $this->isStockProduct;
    }
}
