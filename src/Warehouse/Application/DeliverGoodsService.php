<?php
declare(strict_types=1);

namespace Warehouse\Application;

use Warehouse\Domain\Model\DeliveryNote\DeliveryNote;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNoteRepository;
use Warehouse\Domain\Model\Product\Balance\BalanceRepository;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;

final class DeliverGoodsService
{
    /**
     * @var SalesOrderRepository
     */
    private $salesOrderRepository;
    /**
     * @var DeliveryNoteRepository
     */
    private $deliveryNoteRepository;

    public function __construct(
        SalesOrderRepository $salesOrderRepository,
        DeliveryNoteRepository $deliveryNoteRepository
    ) {
        $this->salesOrderRepository = $salesOrderRepository;
        $this->deliveryNoteRepository = $deliveryNoteRepository;
    }

    public function deliver(string $salesOrderId, array $productsAndQuantities): DeliveryNote
    {
        $salesOrder = $this->salesOrderRepository->getById(
            SalesOrderId::fromString($salesOrderId)
        );

        if ($salesOrder->isNotDeliverable()) {
            throw new \RuntimeException('Sales order is not deliverable.');
        }

        $deliveryNote = DeliveryNote::create(
            $this->deliveryNoteRepository->nextIdentity(),
            $salesOrder->salesOrderId()
        );

        foreach ($salesOrder->lines() as $line) {
            if (!isset($productsAndQuantities[(string)$line->productId()])) {
                continue;
            }

            $receivedQuantity = $productsAndQuantities[(string)$line->productId()];

            $deliveryNote->deliverGoods(
                $line->productId(),
                $receivedQuantity
            );
        }

        $this->deliveryNoteRepository->save($deliveryNote);

        return $deliveryNote;
    }
}
