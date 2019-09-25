<?php
declare(strict_types=1);

namespace Warehouse\Application;

use Warehouse\Domain\Model\Balance\BalanceRepository;
use Warehouse\Domain\Model\Balance\Balance;
use Warehouse\Domain\Model\Product\ProductCreated;
use Warehouse\Domain\Model\ReceiptNote\GoodsReceived;
use Warehouse\Domain\Model\SalesOrder\SalesOrderLineCreated;

final class BalanceProcessManager
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
        $this->balanceRepository->save(new Balance($event->productId()));
    }

    public function whenGoodsReceived(GoodsReceived $event): void
    {
        $balance = $this->balanceRepository->getById($event->productId());

        $balance->increase($event->quantity());

        $this->balanceRepository->save($balance);
    }

    public function whenSalesOrderLineCreated(SalesOrderLineCreated $event): void
    {
        $balance = $this->balanceRepository->getById($event->productId());

        $balance->makeReservation($event->salesOrderId(), $event->quantity());

        $this->balanceRepository->save($balance);
    }
}
