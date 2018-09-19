<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Warehouse\Domain\Model\Product\Product;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\Product\ProductRepository;

final class ProductAggregateRepository extends AggregateRepository implements ProductRepository
{
    public function save(Product $aggregate): void
    {
        $this->store($aggregate);
    }

    public function getById(ProductId $aggregateId): Product
    {
        $aggregate = $this->load((string)$aggregateId);

        if (!$aggregate instanceof Product) {
            throw AggregateNotFound::with(Product::class, (string)$aggregateId);
        }

        return $aggregate;
    }

    public function nextIdentity(): ProductId
    {
        return ProductId::fromString($this->generateUuid());
    }
}
