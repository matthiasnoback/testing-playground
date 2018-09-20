<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Product;

interface ProductRepository
{
    public function save(Product $aggregate): void;

    public function getById(ProductId $aggregateId): Product;

    public function nextIdentity(): ProductId;
}
