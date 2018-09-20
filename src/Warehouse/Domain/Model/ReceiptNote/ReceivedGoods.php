<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\ReceiptNote;

use Assert\Assertion;
use Warehouse\Domain\Model\Product\ProductId;

final class ReceivedGoods
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var int
     */
    private $quantity;

    public function __construct(ProductId $productId, int $quantity)
    {
        Assertion::greaterThan($quantity, 0);
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
}
