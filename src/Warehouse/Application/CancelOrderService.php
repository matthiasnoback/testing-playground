<?php
declare(strict_types=1);

namespace Warehouse\Application;

use Warehouse\Domain\Model\SalesOrder\SalesOrderId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;

/**
 * @author    Philippe Mossière <philippe.mossiere@akeneo.com>
 */
class CancelOrderService
{
    /** @var SalesOrderRepository */
    private $salesOrderRepository;

    public function __construct(SalesOrderRepository $salesOrderRepository)
    {
        $this->salesOrderRepository = $salesOrderRepository;
    }

    public function cancel(string $salesOrderId)
    {
        $salesOrder = $this->salesOrderRepository->getById(SalesOrderId::fromString($salesOrderId));
        $salesOrder->cancel();

        $this->salesOrderRepository->save($salesOrder);
    }
}
