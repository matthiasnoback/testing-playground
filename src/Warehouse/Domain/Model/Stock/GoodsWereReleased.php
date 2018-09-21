<?php
/**
 * Created by PhpStorm.
 * User: wardvanhooreweghe
 * Date: 21/09/2018
 * Time: 14:42
 */

namespace Warehouse\Domain\Model\Stock;


use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

class GoodsWereReleased
{
    /**
     * @var StockId
     */
    private $stockId;
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    public function __construct(
        StockId $stockId,
        SalesOrderId $salesOrderId
    ) {
        $this->stockId = $stockId;
        $this->salesOrderId = $salesOrderId;
    }

    /**
     * @return StockId
     */
    public function getStockId(): StockId
    {
        return $this->stockId;
    }

    /**
     * @return SalesOrderId
     */
    public function getSalesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }


}
