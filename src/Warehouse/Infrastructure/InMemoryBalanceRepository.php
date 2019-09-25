<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use RuntimeException;
use Warehouse\Application\ReadModel\Balance;
use Warehouse\Application\ReadModel\BalanceRepository;
use Warehouse\Domain\Model\Product\ProductId;

final class InMemoryBalanceRepository implements BalanceRepository
{
    /**
     * @var array&Balance[]
     */
    private $balances = [];

    public function getById(ProductId $productId): Balance
    {
        if (!isset($this->balances[(string)$productId])) {
            throw new RuntimeException('Balance not found for product ' . $productId);
        }

        return $this->balances[(string)$productId];
    }

    public function save(Balance $balance): void
    {
        $this->balances[(string)$balance->productId()] = $balance;
    }
}
