<?php

namespace Warehouse\Domain\Model\SalesOrder;


class SalesOrderWasCanceled
{
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    public function __construct(SalesOrderId $salesOrderId)
    {
        $this->salesOrderId = $salesOrderId;
    }

    /**
     * @return SalesOrderId
     */
    public function getSalesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }

}
