<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use Warehouse\Application\Balance;
use Warehouse\Application\BalanceRepository;
use Warehouse\Application\BalanceSubscriber;
use Warehouse\Application\CreateProductService;
use Warehouse\Application\DeliverGoodsService;
use Warehouse\Application\PlacePurchaseOrderService;
use Warehouse\Application\PlaceSalesOrderService;
use Warehouse\Application\ReceiveGoodsService;
use Warehouse\Application\YoloSubscriber;
use Warehouse\Domain\Model\Balance\ReservationAccepted;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNoteRepository;
use Warehouse\Domain\Model\DeliveryNote\GoodsDelivered;
use Warehouse\Domain\Model\Product\ProductCreated;
use Warehouse\Domain\Model\Product\ProductRepository;
use Warehouse\Domain\Model\ReceiptNote\GoodsReceived;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNoteRepository;
use Warehouse\Domain\Model\SalesOrder\SalesOrderLineAdded;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;

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

    public function receiveGoods(): ReceiveGoodsService
    {
        return new ReceiveGoodsService(
            $this->purchaseOrderRepository(),
            $this->receiptNoteRepository(),
            $this->productRepository()
        );
    }

    private function receiptNoteRepository(): ReceiptNoteRepository
    {
        static $service;

        return $service ?: $service = new ReceiptNoteAggregateRepository($this->eventDispatcher());
    }

    public function deliverGoods(): DeliverGoodsService
    {
        return new DeliverGoodsService(
            $this->salesOrderRepository(),
            $this->deliveryNoteRepository(),
            $this->productRepository()
        );
    }

    private function productRepository(): ProductRepository
    {
        static $service;

        return $service ?: $service = new ProductAggregateRepository($this->eventDispatcher());
    }

    private function purchaseOrderRepository(): PurchaseOrderAggregateRepository
    {
        static $service;

        return $service ?: $service = new PurchaseOrderAggregateRepository($this->eventDispatcher());
    }

    private function salesOrderRepository(): SalesOrderRepository
    {
        static $service;

        return $service ?: $service = new SalesOrderAggregateRepository($this->eventDispatcher());
    }

    public function balanceRepository(): BalanceRepository
    {
        static $service;

        return $service ?: $service = new InMemoryBalanceRepository();
    }

    public function balanceAggregateRepository(): BalanceAggregateRepository
    {
        static $service;

        return $service ?: $service = new BalanceAggregateRepository($this->eventDispatcher());
    }

    private function balanceSubscriber(): BalanceSubscriber
    {
        static $service;

        return $service ?: $service = new BalanceSubscriber($this->balanceRepository());
    }

    private function yoloSubscriber(): YoloSubscriber
    {
        static $service;

        return $service ?: $service = new YoloSubscriber(
            $this->balanceAggregateRepository(),
            $this->salesOrderRepository()
        );
    }

    private function eventDispatcher(): EventDispatcher
    {
        static $service;

        if ($service === null) {
            $service = new EventDispatcher();

            $service->registerSubscriber(ProductCreated::class, [$this->balanceSubscriber(), 'onProductCreated']);
            $service->registerSubscriber(GoodsReceived::class, [$this->balanceSubscriber(), 'onGoodsReceived']);
            $service->registerSubscriber(GoodsDelivered::class, [$this->balanceSubscriber(), 'onGoodsDelivered']);
            $service->registerSubscriber(ProductCreated::class, [$this->yoloSubscriber(), 'onProductCreated']);
            $service->registerSubscriber(GoodsReceived::class, [$this->yoloSubscriber(), 'onGoodsReceived']);
            $service->registerSubscriber(ReservationAccepted::class, [$this->yoloSubscriber(), 'onReservationAccepted']);
            $service->registerSubscriber(SalesOrderLineAdded::class, [$this->yoloSubscriber(), 'onSalesOrderLineAdded']);
        }

        return $service;
    }

    private function deliveryNoteRepository(): DeliveryNoteRepository
    {
        static $service;

        return $service ?: $service = new DeliveryNoteAggregateRepository($this->eventDispatcher());
    }

}
