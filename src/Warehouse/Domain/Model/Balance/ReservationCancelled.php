<?php

namespace Warehouse\Domain\Model\Balance;


use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

class ReservationCancelled
{
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    /**
     * @var ProductId
     */
    private $productId;

    public function __construct(SalesOrderId $salesOrderId, ProductId $productId)
    {
        $this->salesOrderId = $salesOrderId;
        $this->productId = $productId;
    }

    /**
     * @return SalesOrderId
     */
    public function getSalesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}