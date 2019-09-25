<?php

namespace Warehouse\Domain\Model\Balance;

use Warehouse\Domain\Model\Product\ProductId;

interface BalanceRepository
{
    public function save(Balance $balance): void;

    public function getById(ProductId $productId): Balance;
}
