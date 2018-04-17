<?php
declare(strict_types=1);

namespace Application\ReadModel;

use Domain\Model\Product\ProductId;
use Domain\Model\ReceiptNote\ReceiptQuantity;

final class Balance
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var StockLevel
     */
    private $stockLevel;

    private function __construct(ProductId $productId, StockLevel $stockLevel)
    {
        $this->productId = $productId;
        $this->stockLevel = $stockLevel;
    }

    public static function fromScratch(ProductId $productId): Balance
    {
        return new self($productId, StockLevel::initial());
    }

    public function processReceipt(ReceiptQuantity $quantity): Balance
    {
        return new self(
            $this->productId,
            $this->stockLevel->add($quantity->asFloat())
        );
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function stockLevel(): StockLevel
    {
        return $this->stockLevel;
    }
}
