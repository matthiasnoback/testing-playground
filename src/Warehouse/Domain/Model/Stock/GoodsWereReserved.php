<?php

namespace Warehouse\Domain\Model\Stock;

class GoodsWereReserved
{
    private $stockId;
    private $quantity;
    private $salesOrderId;

    public function __construct($stockId, $quantity, $salesOrderId)
    {
        $this->stockId = $stockId;
        $this->quantity = $quantity;
        $this->salesOrderId = $salesOrderId;
    }

    /**
     * @return mixed
     */
    public function getStockId()
    {
        return $this->stockId;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return mixed
     */
    public function getSalesOrderId()
    {
        return $this->salesOrderId;
    }

}
