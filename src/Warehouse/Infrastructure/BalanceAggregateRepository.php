<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\AggregateRepository;
use Warehouse\Domain\Model\Balance\Balance;
use Warehouse\Domain\Model\Balance\BalanceRepository;

final class BalanceAggregateRepository extends AggregateRepository implements BalanceRepository
{
    public function save(Balance $balance): void
    {
        $this->store($balance);
    }
}
