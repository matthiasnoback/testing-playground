<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use Warehouse\Application\CreateProductService;
use Warehouse\Application\PlacePurchaseOrderService;
use Warehouse\Application\PlaceSalesOrderService;
use Warehouse\Domain\Model\Product\ProductRepository;
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

    private function eventDispatcher(): EventDispatcher
    {
        static $service;

        if ($service === null) {
            $service = new EventDispatcher();

            // Register your event subscribers here
            // $service->subscribeToAllEvents(...);
        }

        return $service;
    }
}
