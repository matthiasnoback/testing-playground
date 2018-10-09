<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use Warehouse\Domain\Model\Product\ProductId;

/**
 * @author    Philippe Mossière <philippe.mossiere@akeneo.com>
 */
class SalesOrderLineCancelled
{
    /** @var SalesOrderId */
    private $salesOrderId;
    /** @var ProductId */
    private $productId;

    public function __construct(
        SalesOrderId $salesOrderId,
        ProductId $productId
    ) {
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
