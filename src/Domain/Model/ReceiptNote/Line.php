<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use Domain\Model\Product\ProductId;

final class Line
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var ReceiptQuantity
     */
    private $quantity;

    public function __construct(ProductId $productId, ReceiptQuantity $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantity(): ReceiptQuantity
    {
        return $this->quantity;
    }
}
