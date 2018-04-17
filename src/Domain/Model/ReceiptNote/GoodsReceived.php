<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\Quantity;

final class GoodsReceived
{
    /**
     * @var ReceiptNoteId
     */
    private $receiptNoteId;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var Quantity
     */
    private $quantity;

    public function __construct(ReceiptNoteId $receiptNoteId, ProductId $productId, Quantity $quantity)
    {
        $this->receiptNoteId = $receiptNoteId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }
}
