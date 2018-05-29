<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use Common\Aggregate;
use Common\AggregateId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\PurchaseOrderId;

/**
 * @ORM\Entity()
 */
final class ReceiptNote extends Aggregate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $purchaseOrderId;

    /**
     * @ORM\OneToMany(targetEntity="Line", mappedBy="receiptNote", cascade={"PERSIST"})
     * @var Collection|Line[]
     */
    private $lines;

    private function __construct(ReceiptNoteId $receiptNoteId, PurchaseOrderId $purchaseOrderId)
    {
        $this->id = $receiptNoteId->asString();
        $this->purchaseOrderId = $purchaseOrderId->asString();
        $this->lines = new ArrayCollection();
    }

    public static function create(ReceiptNoteId $receiptNoteId, PurchaseOrderId $purchaseOrderId): ReceiptNote
    {
        $receiptNote = new ReceiptNote($receiptNoteId, $purchaseOrderId);

        $receiptNote->recordThat(new ReceiptNoteCreated($receiptNoteId));

        return $receiptNote;
    }

    public function id(): AggregateId
    {
        return $this->receiptNoteId();
    }

    public function receiptNoteId(): ReceiptNoteId
    {
        return ReceiptNoteId::fromString($this->id);
    }

    public function purchaseOrderId(): PurchaseOrderId
    {
        return PurchaseOrderId::fromString($this->purchaseOrderId);
    }

    public function receive(ProductId $productId, ReceiptQuantity $quantity): void
    {
        $this->lines[] = new Line($this, $productId, $quantity);

        $this->recordThat(new GoodsReceived($this->receiptNoteId(), $this->purchaseOrderId(), $productId, $quantity));
    }

    public function undo(): void
    {
        foreach ($this->lines as $line) {
            $this->recordThat(new ReceiptUndone($this->purchaseOrderId(), $line->productId(), $line->quantity()));
        }
    }

    public function lines(): array
    {
        return $this->lines->toArray();
    }
}
