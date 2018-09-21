<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use Warehouse\Application\CancelSalesOrderService;
use Warehouse\Application\CreateProductService;
use Warehouse\Application\DeliverGoodsService;
use Warehouse\Application\PlacePurchaseOrderService;
use Warehouse\Application\PlaceSalesOrderService;
use Warehouse\Application\ReceiveGoodsService;
use Warehouse\Domain\Model\Product\Balance\BalanceProjector;
use Warehouse\Domain\Model\Product\Balance\BalanceRepository;
use Warehouse\Domain\Model\Product\Balance\InMemoryBalanceRepository;
use Warehouse\Domain\Model\Product\ProductRepository;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNoteRepository;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;
use Warehouse\Domain\Model\Stock\StockManager;
use Warehouse\Domain\Model\Stock\StockRepository;

final class ServiceContainer
{
    public function createProductService(): CreateProductService
    {
        return new CreateProductService($this->productRepository());
    }

    public function placePurchaseOrderService(): PlacePurchaseOrderService
    {
        return new PlacePurchaseOrderService($this->purchaseOrderRepository());
    }

    public function placeSalesOrderService(): PlaceSalesOrderService
    {
        return new PlaceSalesOrderService($this->salesOrderRepository());
    }

    public function deliverGoodsService(): DeliverGoodsService
    {
        return new DeliverGoodsService(
            $this->salesOrderRepository(),
            $this->deliveryNoteRepository(),
            $this->balanceRepository()
        );
    }

    public function receiveGoodsService(): ReceiveGoodsService
    {
        return new ReceiveGoodsService(
            $this->purchaseOrderRepository(),
            $this->receiptNoteRepository()
        );
    }

    private function salesOrderRepository(): SalesOrderRepository
    {
        static $service;

        return $service ?: $service = new SalesOrderAggregateRepository($this->eventDispatcher());
    }

    private function productRepository(): ProductRepository
    {
        static $service;

        return $service ?: $service = new ProductAggregateRepository($this->eventDispatcher());
    }

    private function eventDispatcher(): EventDispatcher
    {
        static $service;

        if ($service === null) {
            $service = new EventDispatcher();

            $service->subscribeToAllEvents($this->balanceProjector());
            $service->subscribeToAllEvents($this->stockManager());
        }

        return $service;
    }

    private function balanceProjector(): BalanceProjector
    {
        static $service;

        return $service ?: $service = new BalanceProjector($this->balanceRepository());
    }

    public function balanceRepository(): BalanceRepository
    {
        static $service;

        return $service ?: $service = new InMemoryBalanceRepository();
    }

    private function deliveryNoteRepository(): DeliveryNoteAggregateRepository
    {
        static $service;

        return $service ?: $service = new DeliveryNoteAggregateRepository($this->eventDispatcher());
    }

    private function receiptNoteRepository(): ReceiptNoteRepository
    {
        static $service;

        return $service ?: $service = new ReceiptNoteAggregateRepository($this->eventDispatcher());
    }

    private function purchaseOrderRepository(): PurchaseOrderAggregateRepository
    {
        static $service;

        return $service ?: $service = new PurchaseOrderAggregateRepository($this->eventDispatcher());
    }

    public function cancelSalesOrderService(): CancelSalesOrderService
    {
        static $service;

        return $service ?: $service = new CancelSalesOrderService($this->salesOrderRepository());
    }

    private function stockManager(): StockManager
    {
        static $service;

        return $service ?: $service = new StockManager(
            $this->stockRepository(),
            $this->salesOrderRepository()
        );
    }

    private function stockRepository(): StockRepository
    {
        static $service;

        return $service ?: $service = new StockAggregateRepository(
            $this->eventDispatcher()
        );
    }
}
