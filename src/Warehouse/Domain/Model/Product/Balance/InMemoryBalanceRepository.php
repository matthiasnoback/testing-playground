<?php

namespace Warehouse\Domain\Model\Product\Balance;

use Warehouse\Domain\Model\Product\ProductId;

class InMemoryBalanceRepository implements BalanceRepository
{
    private $balances = [];

    public function save(Balance $balance): void
    {
        $this->balances[(string) $balance->getProductId()] = $balance;
    }

    public function getByProductId(ProductId $productId): Balance
    {
        return $this->balances[(string) $productId];
    }

    /**
     * @return Balance[]
     */
    public function findAll(): array
    {
        return array_values($this->balances);
    }
}
