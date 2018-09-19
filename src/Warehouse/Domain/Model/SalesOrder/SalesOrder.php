<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;

final class SalesOrder
{
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    /**
     * @var array|SalesOrderLine[]
     */
    private $lines = [];

    private function __construct()
    {
    }

    public static function create(SalesOrderId $salesOrderId): SalesOrder
    {
        $SalesOrder = new self();

        $SalesOrder->salesOrderId = $salesOrderId;

        return $SalesOrder;
    }

    public function id(): AggregateId
    {
        return $this->salesOrderId;
    }

    public function addLine(ProductId $productId, int $quantity): void
    {
        $this->lines[] = new SalesOrderLine($productId, $quantity);
    }

    /**
     * @return array|SalesOrderLine[]
     */
    public function lines(): array
    {
        return $this->lines;
    }
}
