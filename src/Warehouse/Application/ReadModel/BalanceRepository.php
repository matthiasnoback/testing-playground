<?php

namespace Warehouse\Application\ReadModel;

use RuntimeException;
use Warehouse\Domain\Model\Product\ProductId;

interface BalanceRepository
{
    /**
     * @param ProductId $productId
     * @return Balance
     * @throws RuntimeException
     */
    public function getById(ProductId $productId): Balance;

    public function save(Balance $balance): void;
}
