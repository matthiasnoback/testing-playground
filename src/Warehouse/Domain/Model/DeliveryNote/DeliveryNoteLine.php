<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\DeliveryNote;

use Warehouse\Domain\Model\Product\ProductId;

final class DeliveryNoteLine
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
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
