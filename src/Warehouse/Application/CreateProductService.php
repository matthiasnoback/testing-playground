<?php
declare(strict_types=1);

namespace Warehouse\Application;

use Warehouse\Domain\Model\Product\Product;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\Product\ProductRepository;

final class CreateProductService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create($description): ProductId
    {
        $productId = $this->productRepository->nextIdentity();

        $product = Product::create(
            $productId,
            $description
        );

        $this->productRepository->save($product);

        return $productId;
    }
}
