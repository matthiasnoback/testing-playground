<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

interface SalesOrderRepository
{
    public function save(SalesOrder $aggregate): void;

    public function getById(SalesOrderId $aggregateId): SalesOrder;

    public function nextIdentity(): SalesOrderId;
}
