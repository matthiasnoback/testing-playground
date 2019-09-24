<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Product;

use RuntimeException;

interface ProductRepository
{
    public function save(Product $aggregate): void;

    /**
     * @throws RuntimeException
     */
    public function getById(ProductId $aggregateId): Product;

    public function nextIdentity(): ProductId;
}
