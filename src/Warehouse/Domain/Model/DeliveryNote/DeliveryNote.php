<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\DeliveryNote;

use Common\Aggregate;
use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

final class DeliveryNote extends Aggregate
{
    /**
     * @var DeliveryNoteId
     */
    private $deliveryNoteId;

    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    /**
     * @var array|DeliveredGoods[]
     */
    private $deliveredGoods = [];

    private function __construct(DeliveryNoteId $deliveryNoteId, SalesOrderId $salesOrderId)
    {
        $this->deliveryNoteId = $deliveryNoteId;
        $this->salesOrderId = $salesOrderId;
    }

    public static function create(DeliveryNoteId $deliveryNoteId, SalesOrderId $salesOrderId): DeliveryNote
    {
        return new self($deliveryNoteId, $salesOrderId);
    }

    public function id(): AggregateId
    {
        return $this->deliveryNoteId;
    }

    public function deliverGoods(ProductId $productId, $quantity): void
    {
        $this->deliveredGoods[] = new DeliveredGoods($productId, $quantity);

        $this->recordThat(new GoodsDelivered($productId, $quantity));
    }

    public function salesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }

    public function deliveredGoods()
    {
        return $this->deliveredGoods;
    }
}
