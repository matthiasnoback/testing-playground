<?php
declare(strict_types=1);

namespace Application\ReadModel;

use Common\EventDispatcher\EventDispatcher;
use Domain\Model\Product\ProductId;

final class BalanceRepository
{
    /**
     * @var Balance[]
     */
    private $balances = [];
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getBalanceFor(ProductId $productId): Balance
    {
        if (!isset($this->balances[(string)$productId])) {
            $this->balances[(string)$productId] = Balance::fromScratch($productId);
        }

        return $this->balances[(string)$productId];
    }

    public function save(Balance $balance): void
    {
        $this->balances[(string)$balance->productId()] = $balance;

        $this->eventDispatcher->dispatch(new BalanceUpdated($balance->productId(), $balance->stockLevel()));
    }
}
