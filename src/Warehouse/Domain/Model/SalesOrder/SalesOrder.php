<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use Common\Aggregate;
use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;

final class SalesOrder extends Aggregate
{
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    /**
     * @var SalesOrderLine[]
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

    public function salesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }

    public function addLine(ProductId $productId, int $quantity): void
    {
        $this->lines[] = new SalesOrderLine($productId, $quantity);
    }

    /**
     * @return SalesOrderLine[]
     */
    public function lines(): array
    {
        return $this->lines;
    }

    public function place(): void
    {
        $this->recordThat(new SalesOrderWasPlaced($this->salesOrderId));
    }

    private function isDeliverable(): bool
    {
        foreach ($this->lines() as $line) {
            if (!$line->isReserved()) {
                return false;
            }
        }
        return true;
    }

    public function isNotDeliverable(): bool
    {
       return ! $this->isDeliverable();
    }

    public function markProductAsReserved(ProductId $productId): void
    {
        foreach ($this->lines as $line) {
            if ($line->productId() == $productId) {
                $line->markReserved();
            }
        }
    }

    public function cancel(): void
    {
        $this->recordThat(new SalesOrderWasCanceled($this->salesOrderId));
    }

    public function markProductAsReleased(ProductId $productId): void
    {
        foreach ($this->lines as $line) {
            if ($line->productId() == $productId) {
                $line->markReleased();
            }
        }
    }
}
