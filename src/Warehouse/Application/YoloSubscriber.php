<?php

declare(strict_types=1);

/*
 * This file is part of the Akeneo PIM Enterprise Edition.
 *
 * (c) 2018 Akeneo SAS (http://www.akeneo.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Warehouse\Application;

use Warehouse\Domain\Model\Balance\ReservationAccepted;
use Warehouse\Domain\Model\Product\ProductCreated;
use Warehouse\Domain\Model\Balance\BalanceRepository;
use Warehouse\Domain\Model\Balance\Balance;
use Warehouse\Domain\Model\ReceiptNote\GoodsReceived;
use Warehouse\Domain\Model\SalesOrder\SalesOrderLineAdded;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;

class YoloSubscriber
{
    /** @var BalanceRepository */
    private $balanceRepository;

    /** @var SalesOrderRepository */
    private $salesOrderRepository;

    public function __construct(BalanceRepository $balanceRepository, SalesOrderRepository $salesOrderRepository)
    {
        $this->balanceRepository = $balanceRepository;
        $this->salesOrderRepository = $salesOrderRepository;
    }

    public function onProductCreated(ProductCreated $productCreated)
    {
        $balance = new Balance($productCreated->getProductId());

        $this->balanceRepository->save($balance);
    }

    public function onGoodsReceived(GoodsReceived $goodsReceived)
    {
        $balance = $this->balanceRepository->getByProductId($goodsReceived->getProductId());

        $balance->increase($goodsReceived->getQuantity());

        $this->balanceRepository->save($balance);
    }

    public function onReservationAccepted(ReservationAccepted $reservationAccepted): void
    {
        $salesOrder = $this->salesOrderRepository->getById($reservationAccepted->getSalesOrderId());

        $salesOrder->markLineAsDeliverable($reservationAccepted->getProductId());
        $this->salesOrderRepository->save($salesOrder);
    }

    public function onSalesOrderLineAdded(SalesOrderLineAdded $lineAdded): void
    {
        $balance = $this->balanceRepository->getByProductId($lineAdded->getProductId());
        $balance->makeReservation($lineAdded->getSalesOrderId(), $lineAdded->getQuantity());

        $this->balanceRepository->save($balance);
    }
}
