<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\Stock\Stock;
use Warehouse\Domain\Model\Stock\StockId;
use Warehouse\Domain\Model\Stock\StockRepository;

final class StockAggregateRepository extends AggregateRepository implements StockRepository
{
    public function save(Stock $aggregate): void
    {
        $this->store($aggregate);
    }

    public function getByProductId(ProductId $productId): Stock
    {
        return $this->getById(StockId::fromProductId($productId));
    }

    private function getById(StockId $aggregateId): Stock
    {
        $aggregate = $this->load((string)$aggregateId);

        if (!$aggregate instanceof Stock) {
            throw AggregateNotFound::with(Stock::class, (string)$aggregateId);
        }

        return $aggregate;
    }
}
