<?php
declare(strict_types=1);

namespace Application\ReadModel;

use Domain\Model\Product\ProductId;

final class BalanceUpdated
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var StockLevel
     */
    private $stockLevel;

    public function __construct(ProductId $productId, StockLevel $stockLevel)
    {
        $this->productId = $productId;
        $this->stockLevel = $stockLevel;
    }
}
