<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\ReceiptNote;

use Common\Aggregate;
use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;

final class ReceiptNote extends Aggregate
{
    /**
     * @var ReceiptNoteId
     */
    private $receiptNoteId;

    /**
     * @var PurchaseOrderId
     */
    private $purchaseOrderId;

    /**
     * @var array&ReceiptNoteLine[]
     */
    private $lines;

    public function __construct(
        ReceiptNoteId $receiptNoteId,
        PurchaseOrderId $purchaseOrderId
    ) {
        $this->receiptNoteId = $receiptNoteId;
        $this->purchaseOrderId = $purchaseOrderId;
        $this->lines = [];
    }

    public function addLine(ProductId $productId, $quantity)
    {
        $this->lines[(string)$productId] = new ReceiptNoteLine($productId, $quantity);

        $this->recordThat(new GoodsReceived($productId, $quantity));
    }

    public function id(): AggregateId
    {
        return $this->receiptNoteId;
    }
}
