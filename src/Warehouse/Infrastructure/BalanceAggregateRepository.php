<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Warehouse\Domain\Model\Balance\Balance;
use Warehouse\Domain\Model\Balance\BalanceRepository;
use Warehouse\Domain\Model\Product\ProductId;

final class BalanceAggregateRepository extends AggregateRepository implements BalanceRepository
{
    public function save(Balance $aggregate): void
    {
        $this->store($aggregate);
    }

    public function getByProductId(ProductId $aggregateId): Balance
    {
        $aggregate = $this->load((string)$aggregateId);

        if (!$aggregate instanceof Balance) {
            throw AggregateNotFound::with(Balance::class, (string)$aggregateId);
        }

        return $aggregate;
    }
}
