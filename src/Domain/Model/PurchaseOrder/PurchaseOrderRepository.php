<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Ramsey\Uuid\Uuid;

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

    public function nextIdentity(): PurchaseOrderId
    {
        return PurchaseOrderId::fromString(Uuid::uuid4()->toString());
    }
}
