<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\Product\Product;
use Domain\Model\Product\ProductId;

final class Line
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var float
     */
    private $quantity;

    public function __construct(Product $product, float $quantity)
    {
        if (!$product->isStockProduct()) {
            throw new \InvalidArgumentException('You can only add stock products to a purchase order.');
        }

        if ($quantity <= 0) {
            throw new \InvalidArgumentException('You can only order quantities larger than 0.');
        }

        $this->productId = $product->productId();
        $this->quantity = $quantity;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }
}
