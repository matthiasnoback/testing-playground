<?php
declare(strict_types=1);

namespace Domain\Model\Product;

final class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $isStockProduct;

    /**
     * @var bool
     */
    private $useBatchNumbers;

    public function __construct(int $id, string $name, bool $isStockProduct, bool $useBatchNumbers)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isStockProduct = $isStockProduct;
        $this->useBatchNumbers = $useBatchNumbers;
    }

    public function isStockProduct(): bool
    {
        return $this->isStockProduct;
    }

    public function equals(Product $product): bool
    {
        return $this->id === $product->id;
    }
}
