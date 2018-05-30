<?php
declare(strict_types=1);

namespace Infrastructure;

use Application\EventSubscriber\UpdatePurchaseOrder;
use Application\EventSubscriber\UpdateStockBalance;
use Application\ReadModel\BalanceRepository;
use Application\Service\CreateReceiptNote\CreateReceiptNoteService;
use Application\Service\PlacePurchaseOrder\PlacePurchaseOrderService;
use Common\EventDispatcher\EventDispatcher;
use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\ReceiptNote\GoodsReceived;
use Domain\Model\ReceiptNote\ReceiptNoteRepository;

final class ServiceContainer
{
    public function placePurchaseOrderService(): PlacePurchaseOrderService
    {
        static $service;

        return $service ?: $service = new PlacePurchaseOrderService($this->purchaseOrderRepository());
    }

    public function createReceiptNoteService(): CreateReceiptNoteService
    {
        static $service;

        return $service ?: $service = new CreateReceiptNoteService($this->purchaseOrderRepository(), $this->receiptNoteRepository());
    }

    public function purchaseOrderRepository(): PurchaseOrderRepository
    {
        static $service;

        return $service ?: $service = new PurchaseOrderRepository($this->eventDispatcher());
    }

    private function receiptNoteRepository(): ReceiptNoteRepository
    {
        static $service;

        return $service ?: $service = new ReceiptNoteRepository($this->eventDispatcher());
    }

    public function balanceRepository(): BalanceRepository
    {
        static $service;

        return $service ?: $service = new BalanceRepository($this->eventDispatcher());
    }

    private function eventDispatcher(): EventDispatcher
    {
        static $service;

        if ($service === null) {
            $service = new EventDispatcher();
            $service->registerSubscriber(
                GoodsReceived::class,
                [$this->updatePurchaseOrderSubscriber(), 'whenGoodsReceived']
            );
            $service->registerSubscriber(
                GoodsReceived::class,
                [$this->updateStockBalanceSubscriber(), 'whenGoodsReceived']
            );
        }

        return $service;
    }

    private function updatePurchaseOrderSubscriber(): UpdatePurchaseOrder
    {
        static $service;

        return $service ?: $service = new UpdatePurchaseOrder($this);
    }

    private function updateStockBalanceSubscriber(): UpdateStockBalance
    {
        static $service;

        return $service ?: $service = new UpdateStockBalance($this);
    }
}
