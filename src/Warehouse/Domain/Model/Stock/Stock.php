<?php

namespace Warehouse\Domain\Model\Stock;

use Common\Aggregate;
use Common\AggregateId;
use http\Exception\RuntimeException;
use Warehouse\Domain\Model\Product\Balance\StockWasDecreased;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

class Stock extends Aggregate
{

    private const ZERO = 0;

    /**
     * @var int
     */
    private $availableStockQuantity;

    /**
     * @var int
     */
    private $reservedQuantity;

    /**
     * @var StockId
     */
    private $stockId;

    public function __construct(StockId $stockId, int $quantityInStock)
    {
        $this->stockId = $stockId;
        $this->availableStockQuantity = $quantityInStock;
    }

    public function id(): AggregateId
    {
        return $this->stockId;
    }

    public static function initialize(ProductId $productId): Stock
    {
        return new self(StockId::fromProductId($productId), self::ZERO);
    }

    public function reserveForSalesOrder(int $quantity, SalesOrderId $salesOrderId): void
    {
        if ($quantity > $this->availableStockQuantity) {
            $this->recordThat(new GoodsReservationFailed($this->stockId, $quantity, $salesOrderId));
            return;
        }
        $this->reservedQuantity[(string)$salesOrderId] = $quantity;
        $this->decreaseAvailableStockQuantity($quantity);
        $this->recordThat(new GoodsWereReserved($this->stockId, $quantity, $salesOrderId));
    }

    public function releaseForSalesOrder(SalesOrderId $salesOrderId): void
    {
        if (isset($this->reservedQuantity[(string)$salesOrderId])) {
            $this->increaseAvailableStockQuantity($this->reservedQuantity[(string)$salesOrderId]);
            unset($this->reservedQuantity[(string)$salesOrderId]);
        }
        $this->recordThat(new GoodsWereReleased($this->stockId, $salesOrderId));
    }

    public function increaseAvailableStockQuantity(int $quantity): void
    {
        $this->availableStockQuantity += $quantity;
        $this->recordThat(
            new StockWasIncreased(
                ProductId::fromStockId($this->stockId),
                $quantity)
        );
    }

    public function decreaseAvailableStockQuantity(int $quantity): void
    {
        $this->availableStockQuantity -= $quantity;
        $this->recordThat(
            new StockWasDecreased(
                ProductId::fromStockId($this->stockId),
                $quantity)
        );
    }
}
