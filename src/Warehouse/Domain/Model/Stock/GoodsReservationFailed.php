<?php

namespace Warehouse\Domain\Model\Stock;

class GoodsReservationFailed
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
}
