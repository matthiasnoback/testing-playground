<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use Doctrine\ORM\Mapping as ORM;
use Domain\Model\Product\ProductId;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ReceiptNoteLine")
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
     * @ORM\ManyToOne(targetEntity="ReceiptNote")
     * @var ReceiptNote
     */
    private $receiptNote;

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

    public function __construct(ReceiptNote $receiptNote, ProductId $productId, ReceiptQuantity $quantity)
    {
        $this->receiptNote = $receiptNote;
        $this->productId = $productId->asString();
        $this->quantity = $quantity->asInt();
    }

    public function productId(): ProductId
    {
        return ProductId::fromString($this->productId);
    }

    public function quantity(): ReceiptQuantity
    {
        return new ReceiptQuantity($this->quantity);
    }
}
