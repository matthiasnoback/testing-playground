<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\Quantity;

final class Line
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var Quantity
     */
    private $quantity;

    public function __construct(ProductId $productId, Quantity $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
}
