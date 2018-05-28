<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\Product\ProductId;
use Domain\Model\ReceiptNote\ReceiptQuantity;

final class Line
{
    /**
     * @var int
     */
    private $lineNumber;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var OrderedQuantity
     */
    private $quantity;

    /**
     * @var QuantityReceived
     */
    private $quantityReceived;

    public function __construct(int $lineNumber, ProductId $productId, OrderedQuantity $quantity)
    {
        $this->lineNumber = $lineNumber;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->quantityReceived = new QuantityReceived(0.0);
    }

    public function lineNumber(): int
    {
        return $this->lineNumber;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function processReceipt(ReceiptQuantity $quantity): void
    {
        $this->quantityReceived = $this->quantityReceived->add($quantity);
    }

    public function undoReceipt($quantity): void
    {
        $this->quantityReceived = $this->quantityReceived->subtract($quantity);
    }

    public function isFullyDelivered(): bool
    {
        return $this->quantityReceived->asFloat() >= $this->quantity->asFloat();
    }
}
