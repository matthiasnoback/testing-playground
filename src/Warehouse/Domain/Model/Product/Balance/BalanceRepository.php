<?php

namespace Warehouse\Domain\Model\Product\Balance;

use Warehouse\Domain\Model\Product\ProductId;

interface BalanceRepository
{
    public function save(Balance $balance): void;

    public function getByProductId(ProductId $productId): Balance;

    /**
     * @return Balance[]
     */
    public function findAll(): array;
}
