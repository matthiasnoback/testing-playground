<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\PurchaseOrder;

use RuntimeException;

interface PurchaseOrderRepository
{
    public function save(PurchaseOrder $aggregate): void;

    /**
     * @throws RuntimeException
     */
    public function getById(PurchaseOrderId $aggregateId): PurchaseOrder;

    public function nextIdentity(): PurchaseOrderId;
}
