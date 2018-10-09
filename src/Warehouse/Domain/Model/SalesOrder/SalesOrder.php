<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use Assert\Assertion;
use Common\Aggregate;
use Common\AggregateId;
use LogicException;
use Warehouse\Domain\Model\Product\ProductId;

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
        $salesOrder = new self();

        $salesOrder->salesOrderId = $salesOrderId;

        $salesOrder->recordThat(new SalesOrderCreated($salesOrderId));

        return $salesOrder;
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

        $this->lines[(string) $productId] = new SalesOrderLine($productId, $quantity);
    }

    public function place(): void
    {
        if (\count($this->lines) === 0) {
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

    public function linesToArray(): array
    {
        $array = [];
        foreach ($this->lines as $line) {
            $array[(string) $line->productId()] = $line->quantity();
        }

        return $array;
    }

    public function isDeliverable(): bool
    {
        foreach ($this->lines as $line) {
            if (!$line->isDeliverable()) {
                return false;
            }
        }

        return true;
    }

    public function markLineAsDeliverable(ProductId $productId): void
    {
        if (array_key_exists((string) $productId, $this->lines)) {
            $this->lines[(string) $productId]->markAsDeliverable();
        }
    }
}
