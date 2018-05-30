<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Doctrine\ORM\Mapping as ORM;
use Domain\Model\Product\ProductId;
use Domain\Model\ReceiptNote\ReceiptQuantity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="PurchaseOrderLine")
 */
final class Line
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PurchaseOrder")
     * @var PurchaseOrder
     */
    private $purchaseOrder;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $lineNumber;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $productId;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $quantityReceived;

    public function __construct(PurchaseOrder $purchaseOrder, int $lineNumber, ProductId $productId, OrderedQuantity $quantity)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->lineNumber = $lineNumber;
        $this->productId = $productId->asString();
        $this->quantity = $quantity->asInt();
        $this->quantityReceived = 0;
    }

    public function lineNumber(): int
    {
        return $this->lineNumber;
    }

    public function productId(): ProductId
    {
        return ProductId::fromString($this->productId);
    }

    public function processReceipt(ReceiptQuantity $quantity): void
    {
        $this->quantityReceived = $this->quantityReceived()->add($quantity)->asInt();
    }

    public function undoReceipt($quantity): void
    {
        $this->quantityReceived = $this->quantityReceived()->subtract($quantity)->asInt();
    }

    public function isFullyDelivered(): bool
    {
        return $this->quantityReceived()->asInt() >= $this->quantity()->asInt();
    }

    private function quantityReceived(): QuantityReceived
    {
        return new QuantityReceived($this->quantityReceived);
    }

    private function quantity(): OrderedQuantity
    {
        return new OrderedQuantity($this->quantity);
    }
}
