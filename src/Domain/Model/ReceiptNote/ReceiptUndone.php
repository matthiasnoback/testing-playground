<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use function Common\CommandLine\line;
use function Common\CommandLine\make_red;
use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\PurchaseOrderId;

final class ReceiptUndone
{
    /**
     * @var PurchaseOrderId
     */
    private $purchaseOrderId;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var ReceiptQuantity
     */
    private $quantity;

    public function __construct(PurchaseOrderId $purchaseOrderId, ProductId $productId, ReceiptQuantity $quantity)
    {
        $this->purchaseOrderId = $purchaseOrderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function purchaseOrderId(): PurchaseOrderId
    {
        return $this->purchaseOrderId;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantity(): ReceiptQuantity
    {
        return $this->quantity;
    }

    public function __toString()
    {
        return line(
            make_red('Receipt undone'),
            sprintf(': product %s, quantity %s', $this->productId, $this->quantity->asInt())
        );
    }
}
