<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Warehouse\Domain\Model\SalesOrder\SalesOrder;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;

final class SalesOrderAggregateRepository extends AggregateRepository implements SalesOrderRepository
{
    public function save(SalesOrder $aggregate): void
    {
        $this->store($aggregate);
    }

    public function getById(SalesOrderId $aggregateId): SalesOrder
    {
        $aggregate = $this->load((string)$aggregateId);

        if (!$aggregate instanceof SalesOrder) {
            throw AggregateNotFound::with(SalesOrder::class, (string)$aggregateId);
        }

        return $aggregate;
    }

    public function nextIdentity(): SalesOrderId
    {
        return SalesOrderId::fromString($this->generateUuid());
    }
}
