<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Balance;

use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

final class StockReservationRejected
{
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var int
     */
    private $quantity;

    public function __construct(
        SalesOrderId $salesOrderId,
        ProductId $productId,
        int $quantity
    ) {
        $this->salesOrderId = $salesOrderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function salesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
