<?php
declare(strict_types=1);

namespace Warehouse\Application\ReadModel;

use Warehouse\Domain\Model\Product\ProductId;

final class Balance
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var int
     */
    private $quantityInStock;

    public function __construct(ProductId $productId)
    {
        $this->productId = $productId;
        $this->quantityInStock = 0;
    }

    public function increase(int $quantity): void
    {
        $this->quantityInStock += $quantity;
    }

    public function decrease(int $quantity): void
    {
        $this->quantityInStock -= $quantity;
    }

    public function quantityInStock(): int
    {
        return $this->quantityInStock;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }
}
