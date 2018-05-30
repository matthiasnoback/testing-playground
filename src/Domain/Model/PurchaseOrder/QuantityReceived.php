<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\ReceiptNote\ReceiptQuantity;

final class QuantityReceived
{
    /**
     * @var int
     */
    private $quantity;

    public function __construct(int $quantity)
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Quantity received should be larger than or equal to 0.');
        }

        $this->quantity = $quantity;
    }

    public function asInt(): int
    {
        return $this->quantity;
    }

    public function add(ReceiptQuantity $quantity): QuantityReceived
    {
        return new self($this->quantity + $quantity->asInt());
    }

    public function subtract(ReceiptQuantity $quantity): QuantityReceived
    {
        return new self($this->quantity - $quantity->asInt());
    }
}
