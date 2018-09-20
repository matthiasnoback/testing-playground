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
     * @var array|ReceivedGoods[]
     */
    private $receivedGoods = [];

    private function __construct(ReceiptNoteId $receiptNoteId, PurchaseOrderId $purchaseOrderId)
    {
        $this->receiptNoteId = $receiptNoteId;
        $this->purchaseOrderId = $purchaseOrderId;

    }

    public static function create(ReceiptNoteId $receiptNoteId, PurchaseOrderId $purchaseOrderId): ReceiptNote
    {
        return new self($receiptNoteId, $purchaseOrderId);
    }

    public function id(): AggregateId
    {
        return $this->receiptNoteId;
    }

    public function receiveGoods(ProductId $productId, $quantity): void
    {
        $this->receivedGoods[] = new ReceivedGoods($productId, $quantity);

        $this->recordThat(new GoodsReceived($productId, $quantity));
    }

    public function purchaseOrderId(): PurchaseOrderId
    {
        return $this->purchaseOrderId;
    }

    public function receivedGoods()
    {
        return $this->receivedGoods;
    }
}
