<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Balance;

use Common\Aggregate;
use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

final class Balance extends Aggregate
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var int
     */
    private $availableQuantity;

    /**
     * @var int
     */
    private $reservedQuantity;

    public function __construct(ProductId $productId)
    {
        $this->productId = $productId;
        $this->availableQuantity = 0;
        $this->reservedQuantity = 0;
    }

    public function increase(int $quantity): void
    {
        $this->availableQuantity += $quantity;
    }

    public function makeReservation(SalesOrderId $salesOrderId, int $quantity): void
    {
        if ($this->availableQuantity >= $quantity) {
            $this->reservedQuantity += $quantity;
            $this->availableQuantity -= $quantity;
            $this->recordThat(
                new StockReservationAccepted(
                    $salesOrderId,
                    $this->productId,
                    $quantity
                )
            );
        } else {
            $this->recordThat(
                new StockReservationRejected(
                    $salesOrderId,
                    $this->productId,
                    $quantity
                )
            );
        }
    }

    public function id(): AggregateId
    {
        return $this->productId;
    }
}
