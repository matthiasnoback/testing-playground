<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\Product\ProductId;
use Domain\Model\ReceiptNote\ReceiptQuantity;

final class Line
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var OrderedQuantity
     */
    private $quantity;

    /**
     * @var OpenQuantity
     */
    private $quantityOpen;

    public function __construct(ProductId $productId, OrderedQuantity $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->quantityOpen = new OpenQuantity($quantity->asFloat());
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function processReceipt(ReceiptQuantity $quantity): void
    {
        $this->quantityOpen = $this->quantityOpen->subtract($quantity);
    }

    public function isFullyDelivered(): bool
    {
        return $this->quantityOpen->asFloat() === 0.0;
    }
}
