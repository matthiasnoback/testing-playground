<?php

namespace Warehouse\Domain\Model\Product\Balance;

use Warehouse\Domain\Model\DeliveryNote\GoodsDelivered;
use Warehouse\Domain\Model\Product\ProductWasCreated;
use Warehouse\Domain\Model\ReceiptNote\GoodsReceived;

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
        if ($event instanceof GoodsReceived) {
            $this->projectGoodsReceived($event);
        }
        if ($event instanceof GoodsDelivered) {
            $this->projectGoodsDelivered($event);
        }
    }

    protected function projectProductWasCreated(ProductWasCreated $event): void
    {
        $balance = Balance::initialize($event->getProductId());
        $this->balanceRepository->save($balance);
    }

    private function projectGoodsReceived(GoodsReceived $event): void
    {
        $balance = $this->balanceRepository->getByProductId($event->getProductId());
        $this->balanceRepository->save($balance->increasedBy($event->getQuantity()));
    }

    private function projectGoodsDelivered(GoodsDelivered $event): void
    {
        $balance = $this->balanceRepository->getByProductId($event->getProductId());
        $this->balanceRepository->save($balance->decreasedBy($event->getQuantity()));
    }
}
