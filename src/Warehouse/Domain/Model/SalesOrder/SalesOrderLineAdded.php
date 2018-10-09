<?php

declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use Warehouse\Domain\Model\Product\ProductId;

class SalesOrderLineAdded
{
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    /**
     * @var ProductId
     */
    private $productId;

    /** @var int */
    private $quantity;

    public function __construct(SalesOrderId $salesOrderId, ProductId $productId, int $quantity)
    {
        $this->salesOrderId = $salesOrderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
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

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
