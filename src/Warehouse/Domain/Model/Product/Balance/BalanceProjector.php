<?php

namespace Warehouse\Domain\Model\Product\Balance;

use Warehouse\Domain\Model\DeliveryNote\GoodsDelivered;
use Warehouse\Domain\Model\Product\ProductWasCreated;
use Warehouse\Domain\Model\ReceiptNote\GoodsReceived;
use Warehouse\Domain\Model\Stock\GoodsWereReserved;
use Warehouse\Domain\Model\Stock\StockWasIncreased;

class BalanceProjector
{
    /**
     * @var BalanceRepository
     */
    private $balanceRepository;

    public function __construct(BalanceRepository $balanceRepository)
    {
        $this->balanceRepository = $balanceRepository;
    }

    public function __invoke($event)
    {
        if ($event instanceof ProductWasCreated) {
            $this->projectProductWasCreated($event);
        }
        if ($event instanceof StockWasIncreased) {
            $this->projectStockWasIncreased($event);
        }
        if ($event instanceof StockWasDecreased) {
            $this->projectStockWasDecreased($event);
        }
    }

    protected function projectProductWasCreated(ProductWasCreated $event): void
    {
        $balance = Balance::initialize($event->getProductId());
        $this->balanceRepository->save($balance);
    }

    private function projectStockWasIncreased(StockWasIncreased $event): void
    {
        $balance = $this->balanceRepository->getByProductId($event->getProductId());
        $this->balanceRepository->save($balance->increasedBy($event->getQuantity()));
    }

    private function projectStockWasDecreased(StockWasDecreased $event): void
    {
        $balance = $this->balanceRepository->getByProductId($event->getProductId());
        $this->balanceRepository->save($balance->decreasedBy($event->getQuantity()));
    }
}
