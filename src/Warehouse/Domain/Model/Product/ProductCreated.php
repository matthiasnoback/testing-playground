<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Product;

final class ProductCreated
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var string
     */
    private $description;

    public function __construct(ProductId $productId, string $description)
    {
        $this->productId = $productId;
        $this->description = $description;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function description(): string
    {
        return $this->description;
    }
}
