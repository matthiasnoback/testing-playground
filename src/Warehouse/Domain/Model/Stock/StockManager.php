<?php

namespace Warehouse\Domain\Model\Stock;

use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\Product\ProductWasCreated;
use Warehouse\Domain\Model\ReceiptNote\GoodsReceived;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;
use Warehouse\Domain\Model\SalesOrder\SalesOrderWasCanceled;
use Warehouse\Domain\Model\SalesOrder\SalesOrderWasPlaced;

class StockManager
{
    /** @var StockRepository $stockRepository */
    private $stockRepository;
    /** @var SalesOrderRepository $salesOrderRepository */
    private $salesOrderRepository;

    public function __construct(StockRepository $stockRepository, SalesOrderRepository $salesOrderRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->salesOrderRepository = $salesOrderRepository;
    }

    public function __invoke($event)
    {
        if ($event instanceof ProductWasCreated) {
            $this->whenProductWasCreated($event);
        }

        if ($event instanceof SalesOrderWasPlaced) {
            $this->whenSalesOrderWasPlaced($event);
        }

        if ($event instanceof SalesOrderWasCanceled) {
            $this->whenSalesOrderWasCanceled($event);
        }

        if ($event instanceof GoodsWereReserved) {
            $this->whenGoodsWereReserved($event);
        }

        if ($event instanceof GoodsWereReleased) {
            $this->whenGoodsWereReleased($event);
        }

        if ($event instanceof GoodsReceived) {
            $this->whenGoodsReceived($event);
        }
    }

    protected function whenProductWasCreated(ProductWasCreated $event): void
    {
        $stock = Stock::initialize($event->getProductId());
        $this->stockRepository->save($stock);
    }

    private function whenSalesOrderWasPlaced(SalesOrderWasPlaced $event): void
    {
        $salesOrder = $this->salesOrderRepository->getById($event->getSalesOrderId());

        foreach($salesOrder->lines() as $line) {
            $stock = $this->stockRepository->getByProductId($line->productId());
            $stock->reserveForSalesOrder($line->quantity(), $salesOrder->salesOrderId());
            $this->stockRepository->save($stock);
        }
    }

    private function whenSalesOrderWasCanceled(SalesOrderWasCanceled $event): void
    {
        $salesOrder = $this->salesOrderRepository->getById($event->getSalesOrderId());

        foreach($salesOrder->lines() as $line) {
            $stock = $this->stockRepository->getByProductId($line->productId());
            $stock->releaseForSalesOrder($salesOrder->salesOrderId());
            $this->stockRepository->save($stock);
        }
    }

    private function whenGoodsWereReserved(GoodsWereReserved $event): void
    {
        $salesOrder = $this->salesOrderRepository->getById($event->getSalesOrderId());
        $salesOrder->markProductAsReserved(ProductId::fromStockId($event->getStockId()));
        $this->salesOrderRepository->save($salesOrder);
    }

    private function whenGoodsWereReleased(GoodsWereReleased $event): void
    {
        $salesOrder = $this->salesOrderRepository->getById($event->getSalesOrderId());
        $salesOrder->markProductAsReleased(ProductId::fromStockId($event->getStockId()));
        $this->salesOrderRepository->save($salesOrder);
    }

    private function whenGoodsReceived(GoodsReceived $event): void
    {
        $stock = $this->stockRepository->getByProductId($event->getProductId());
        $stock->increaseAvailableStockQuantity($event->getQuantity());
        $this->stockRepository->save($stock);
    }
}
