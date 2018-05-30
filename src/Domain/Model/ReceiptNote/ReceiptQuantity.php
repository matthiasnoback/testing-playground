<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

final class ReceiptQuantity
{
    /**
     * @var int
     */
    private $quantity;

    public function __construct(int $quantity)
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('You can only receive quantities larger than 0.');
        }

        $this->quantity = $quantity;
    }

    public function asInt(): int
    {
        return $this->quantity;
    }
}
