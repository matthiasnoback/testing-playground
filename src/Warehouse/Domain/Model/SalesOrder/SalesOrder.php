<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use Assert\Assertion;
use Common\Aggregate;
use Common\AggregateId;
use LogicException;
use Warehouse\Domain\Model\Product\ProductId;
use function count;

final class SalesOrder extends Aggregate
{
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    /**
     * @var array|SalesOrderLine[]
     */
    private $lines = [];

    /**
     * @var bool
     */
    private $wasPlaced = false;

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

    public function salesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }

    public function addLine(ProductId $productId, int $quantity): void
    {
        Assertion::false($this->wasPlaced, 'You cannot add lines to an already placed sales order.');

        $this->lines[(string)$productId] = new SalesOrderLine($productId, $quantity);
    }

    public function place(): void
    {
        if (count($this->lines) === 0) {
            throw new LogicException('A sales order should have at least one line');
        }

        $this->wasPlaced = true;
    }

    /**
     * @return array|SalesOrderLine[]
     */
    public function lines(): array
    {
        return $this->lines;
    }
}
