<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Balance;

use Warehouse\Domain\Model\Product\ProductId;

final class StockLevelChanged
{
    /**
     * @var ProductId
     */
    private $productId;
    /**
     * @var int
     */
    private $quantityInStock;

    /**
     * @param ProductId $productId
     * @param int $quantityInStock
     */
    public function __construct(ProductId $productId, int $quantityInStock)
    {
        $this->productId = $productId;
        $this->quantityInStock = $quantityInStock;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantityInStock(): int
    {
        return $this->quantityInStock;
    }
}
