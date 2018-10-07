<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\PurchaseOrder;

interface PurchaseOrderRepository
{
    public function save(PurchaseOrder $aggregate): void;

    public function getById(PurchaseOrderId $aggregateId): PurchaseOrder;

    public function nextIdentity(): PurchaseOrderId;
}
