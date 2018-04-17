<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Common\AggregateNotFound;
use Common\AggregateRepository;

final class PurchaseOrderRepository extends AggregateRepository
{
    public function save(PurchaseOrder $aggregate): void
    {
        $this->store($aggregate);
    }

    public function getById(PurchaseOrderId $aggregateId): PurchaseOrder
    {
        $aggregate = $this->load((string)$aggregateId);

        if (!$aggregate instanceof PurchaseOrder) {
            throw AggregateNotFound::with(PurchaseOrder::class, (string)$aggregateId);
        }

        return $aggregate;
    }
}
