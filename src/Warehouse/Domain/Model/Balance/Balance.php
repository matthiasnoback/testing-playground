<?php

declare(strict_types=1);

namespace Warehouse\Domain\Model\Balance;

use Common\Aggregate;
use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

/**
 * @author    Mathias METAYER <mathias.metayer@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Balance extends Aggregate
{
    private $productId;

    private $reservations;

    private $quantityInStock;

    /**
     * @param ProductId $productId
     */
    public function __construct(ProductId $productId)
    {
        $this->productId = $productId;
        $this->reservations = [];
        $this->quantityInStock = 0;
    }

    public function id(): AggregateId
    {
        return $this->productId;
    }

    public function makeReservation(SalesOrderId $salesOrderId, int $quantity): void
    {
        if ($quantity <= $this->quantityInStock) {
            $this->quantityInStock -= $quantity;
            $this->reservations[(string)$salesOrderId] = $quantity;
            $this->recordThat(new ReservationAccepted($salesOrderId, $this->productId));
        } else {
            $this->recordThat(new ReservationRejected($salesOrderId, $this->productId));
        }
    }

    public function cancelReservation(SalesOrderId $salesOrderId): void
    {
        if (array_key_exists((string) $salesOrderId, $this->reservations)) {
            $this->quantityInStock += $this->reservations[(string)$salesOrderId];
            unset($this->reservations[(string)$salesOrderId]);
            $this->recordThat(new ReservationCancelled($salesOrderId, $this->productId));
        }
    }

    public function commitReservation(SalesOrderId $salesOrderId): void
    {
        if (array_key_exists((string) $salesOrderId, $this->reservations)) {
            unset($this->reservations[(string)$salesOrderId]);
        }
    }

    public function increase($quantity): void
    {
        $this->quantityInStock += $quantity;
    }
}
