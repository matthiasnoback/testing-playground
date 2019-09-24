<?php
declare(strict_types=1);

namespace Warehouse\Application;

use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrder;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;

final class PlaceSalesOrderService
{
    /**
     * @var SalesOrderRepository
     */
    private $salesOrderRepository;

    public function __construct(SalesOrderRepository $salesOrderRepository)
    {
        $this->salesOrderRepository = $salesOrderRepository;
    }

    public function place(array $productsAndQuantities): SalesOrderId
    {
        $salesOrderId = $this->salesOrderRepository->nextIdentity();

        $salesOrder = SalesOrder::create(
            $salesOrderId
        );

        foreach ($productsAndQuantities as $productId => $quantity) {
            $salesOrder->addLine(ProductId::fromString($productId), $quantity);
        }

        $salesOrder->place();

        $this->salesOrderRepository->save($salesOrder);

        return $salesOrderId;
    }
}
