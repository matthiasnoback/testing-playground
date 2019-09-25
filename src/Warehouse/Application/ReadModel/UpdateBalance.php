<?php
declare(strict_types=1);

namespace Warehouse\Application\ReadModel;

use Warehouse\Domain\Model\DeliveryNote\GoodsDelivered;
use Warehouse\Domain\Model\Product\ProductCreated;
use Warehouse\Domain\Model\ReceiptNote\GoodsReceived;

final class UpdateBalance
{
    /**
     * @var BalanceRepository
     */
    private $balanceRepository;

    public function __construct(BalanceRepository $balanceRepository)
    {
        $this->balanceRepository = $balanceRepository;
    }

    public function whenProductCreated(ProductCreated $event): void
    {
        $balance = new Balance($event->productId());

        $this->balanceRepository->save($balance);
    }

    public function whenGoodsReceived(GoodsReceived $event): void
    {
        $balance = $this->balanceRepository->getById($event->productId());

        $balance->increase($event->quantity());

        $this->balanceRepository->save($balance);
    }

    public function whenGoodsDelivered(GoodsDelivered $event): void
    {
        $balance = $this->balanceRepository->getById($event->productId());

        $balance->decrease($event->quantity());

        $this->balanceRepository->save($balance);
    }
}
