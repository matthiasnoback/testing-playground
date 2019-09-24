<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use RuntimeException;

interface SalesOrderRepository
{
    public function save(SalesOrder $aggregate): void;

    /**
     * @throws RuntimeException
     */
    public function getById(SalesOrderId $aggregateId): SalesOrder;

    public function nextIdentity(): SalesOrderId;
}
