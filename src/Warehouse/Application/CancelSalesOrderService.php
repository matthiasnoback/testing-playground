<?php

namespace Warehouse\Application;

use Warehouse\Domain\Model\SalesOrder\SalesOrderId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;

class CancelSalesOrderService
{
    /**
     * @var SalesOrderRepository
     */
    private $salesOrderRepository;

    public function __construct(SalesOrderRepository $salesOrderRepository)
    {
        $this->salesOrderRepository = $salesOrderRepository;
    }

    public function cancel(SalesOrderId $salesOrderId): void
    {
        $salesOrder = $this->salesOrderRepository->getById($salesOrderId);
        $salesOrder->cancel();
        $this->salesOrderRepository->save($salesOrder);
    }
}
