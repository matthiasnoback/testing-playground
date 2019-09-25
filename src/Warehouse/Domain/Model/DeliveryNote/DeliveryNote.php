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
     * @var array&DeliveryNoteLine[]
     */
    private $lines;

    public function __construct(
        DeliveryNoteId $deliveryNoteId,
        SalesOrderId $salesOrderId
    ) {
        $this->deliveryNoteId = $deliveryNoteId;
        $this->salesOrderId = $salesOrderId;
        $this->lines = [];
    }

    public function addLine(ProductId $productId, $quantity)
    {
        $this->lines[(string)$productId] = new DeliveryNoteLine($productId, $quantity);

        $this->recordThat(new GoodsDelivered($productId, $quantity));
    }

    public function id(): AggregateId
    {
        return $this->deliveryNoteId;
    }

    /**
     * @return array&DeliveryNoteLine[]
     */
    public function lines(): array
    {
        return $this->lines;
    }
}
