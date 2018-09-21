<?php

namespace Warehouse\Domain\Model\SalesOrder;


class SalesOrderWasPlaced
{

    private $salesOrderId;

    public function __construct(SalesOrderId $salesOrderId)
    {
        $this->salesOrderId = $salesOrderId;
    }

    public function getSalesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }
}
