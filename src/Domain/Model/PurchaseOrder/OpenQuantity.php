<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\ReceiptNote\ReceiptQuantity;

final class OpenQuantity
{
    /**
     * @var float
     */
    private $quantity;

    public function __construct(float $quantity)
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('You can only order quantities larger than or equal to 0.');
        }

        $this->quantity = $quantity;
    }

    public function asFloat(): float
    {
        return $this->quantity;
    }

    public function subtract(ReceiptQuantity $quantity): OpenQuantity
    {
        return new self($this->quantity - $quantity->asFloat());
    }
}
