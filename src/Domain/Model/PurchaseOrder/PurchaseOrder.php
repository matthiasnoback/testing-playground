<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Common\Aggregate;
use Common\AggregateId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Domain\Model\Product\ProductId;
use Domain\Model\ReceiptNote\ReceiptQuantity;
use Domain\Model\Supplier\SupplierId;
use InvalidArgumentException;
use LogicException;

/**
 * @ORM\Entity()
 */
final class PurchaseOrder extends Aggregate
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
    private $supplierId;

    /**
     * @ORM\OneToMany(targetEntity="Line", mappedBy="purchaseOrder", cascade={"PERSIST"})
     * @var Collection|Line[]
     */
    private $lines = [];

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $placed = false;

    private function __construct(PurchaseOrderId $purchaseOrderId, SupplierId $supplierId)
    {
        $this->id = $purchaseOrderId->asString();
        $this->supplierId = $supplierId->asString();
        $this->lines = new ArrayCollection();
    }

    public static function create(PurchaseOrderId $purchaseOrderId, SupplierId $supplierId): PurchaseOrder
    {
        return new self($purchaseOrderId, $supplierId);
    }

    public function addLine(ProductId $productId, OrderedQuantity $quantity): void
    {
        foreach ($this->lines as $line) {
            if ($line->productId()->equals($productId)) {
                throw new InvalidArgumentException('You cannot add the same product twice.');
            }
        }

        $lineNumber = \count($this->lines) + 1;

        $this->lines[] = new Line($this, $lineNumber, $productId, $quantity);
    }

    public function place(): void
    {
        if ($this->placed) {
            throw new LogicException('This purchase order has already been placed.');
        }

        if (\count($this->lines) < 1) {
            throw new LogicException('To place a purchase order, it has to have at least one line.');
        }

        $this->placed = true;

        $this->recordThat(new PurchaseOrderPlaced($this->purchaseOrderId()));
    }

    public function id(): AggregateId
    {
        return $this->purchaseOrderId();
    }

    public function purchaseOrderId(): PurchaseOrderId
    {
        return PurchaseOrderId::fromString($this->id);
    }

    public function supplierId(): SupplierId
    {
        return SupplierId::fromString($this->supplierId);
    }

    /**
     * @return Line[]
     */
    public function lines(): array
    {
        return $this->lines->toArray();
    }

    public function processReceipt(ProductId $productId, ReceiptQuantity $quantity): void
    {
        foreach ($this->lines as $line) {
            if ($line->productId()->equals($productId)) {
                $line->processReceipt($quantity);
            }
        }

        if ($this->isFullyDelivered()) {
            $this->recordThat(new PurchaseOrderCompleted($this->purchaseOrderId()));
        }
    }

    public function undoReceipt(ProductId $productId, ReceiptQuantity $quantity): void
    {
        $wasFullyReceived = $this->isFullyDelivered();

        foreach ($this->lines as $line) {
            if ($line->productId()->equals($productId)) {
                $line->undoReceipt($quantity);
            }
        }

        if ($wasFullyReceived && !$this->isFullyDelivered()) {
            $this->recordThat(new PurchaseOrderReopened($this->purchaseOrderId()));
        }
    }

    public function isFullyDelivered(): bool
    {
        foreach ($this->lines as $line) {
            if (!$line->isFullyDelivered()) {
                return false;
            }
        }

        return true;
    }

    public function lineForProduct(ProductId $productId): Line
    {
        foreach ($this->lines as $line) {
            if ($line->productId()->equals($productId)) {
                return $line;
            }
        }

        throw new \RuntimeException(sprintf(
            'Purchase order "%s" has no line for product "%s"',
            (string)$this->purchaseOrderId(),
            (string)$productId
        ));
    }
}
