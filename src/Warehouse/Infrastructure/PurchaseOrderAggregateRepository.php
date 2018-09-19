<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrder;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderRepository;

final class PurchaseOrderAggregateRepository extends AggregateRepository implements PurchaseOrderRepository
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

    public function nextIdentity(): PurchaseOrderId
    {
        return PurchaseOrderId::fromString($this->generateUuid());
    }
}
