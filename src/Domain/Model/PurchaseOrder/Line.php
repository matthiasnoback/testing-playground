<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\Product\Product;

final class Line
{
    /**
     * @var Product
     */
    private $product;

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

        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function product(): Product
    {
        return $this->product;
    }
}
