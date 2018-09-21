<?php

namespace Warehouse\Domain\Model\Product\Balance;

use Warehouse\Domain\Model\Product\ProductId;

class Balance
{
    private const ZERO = 0;
    private $productId;
    private $quantityInStock;

    private function __construct(ProductId $productId, int $amount)
    {
        $this->productId = $productId;
        $this->quantityInStock = max(0, $amount);
    }

    public static function initialize(ProductId $productId): Balance
    {
        return new self($productId, self::ZERO);
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getQuantityInStock(): int
    {
        return $this->quantityInStock;
    }

    public function increasedBy(int $quantity): Balance
    {
        return new Balance(
            $this->getProductId(),
            $this->getQuantityInStock() + $quantity
        );
    }

    public function decreasedBy(int $quantity): Balance
    {
        return new Balance(
            $this->getProductId(),
            $this->getQuantityInStock() - $quantity
        );
    }
}
