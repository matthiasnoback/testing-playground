<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use Common\Aggregate;
use Common\AggregateId;
use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\PurchaseOrderId;

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
     * @var Line[]
     */
    private $lines;

    private function __construct(ReceiptNoteId $receiptNoteId, PurchaseOrderId $purchaseOrderId)
    {
        $this->receiptNoteId = $receiptNoteId;
        $this->purchaseOrderId = $purchaseOrderId;
    }

    public static function create(ReceiptNoteId $receiptNoteId, PurchaseOrderId $purchaseOrderId): ReceiptNote
    {
        $receiptNote = new ReceiptNote($receiptNoteId, $purchaseOrderId);

        $receiptNote->recordThat(new ReceiptNoteCreated($receiptNoteId));

        return $receiptNote;
    }

    public function id(): AggregateId
    {
        return $this->receiptNoteId;
    }

    public function receiptNoteId(): ReceiptNoteId
    {
        return $this->receiptNoteId;
    }

    public function purchaseOrderId(): PurchaseOrderId
    {
        return $this->purchaseOrderId;
    }

    public function receive(ProductId $productId, ReceiptQuantity $quantity): void
    {
        $this->lines[] = new Line($productId, $quantity);

        $this->recordThat(new GoodsReceived($this->receiptNoteId, $productId, $quantity));
    }

    public function lines(): array
    {
        return $this->lines;
    }
}
