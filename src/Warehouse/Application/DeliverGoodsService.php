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

    /**
     * @var BalanceRepository
     */
    private $balanceRepository;

    public function __construct(
        SalesOrderRepository $salesOrderRepository,
        DeliveryNoteRepository $deliveryNoteRepository,
        BalanceRepository $balanceRepository
    ) {
        $this->salesOrderRepository = $salesOrderRepository;
        $this->deliveryNoteRepository = $deliveryNoteRepository;
        $this->balanceRepository = $balanceRepository;
    }

    public function deliver(string $salesOrderId, array $productsAndQuantities): DeliveryNote
    {
        $salesOrder = $this->salesOrderRepository->getById(
            SalesOrderId::fromString($salesOrderId)
        );

        $deliveryNote = DeliveryNote::create(
            $this->deliveryNoteRepository->nextIdentity(),
            $salesOrder->salesOrderId()
        );

        foreach ($salesOrder->lines() as $line) {
            if (!isset($productsAndQuantities[(string)$line->productId()])) {
                continue;
            }

            $balance = $this->balanceRepository->getByProductId($line->productId());

            $receivedQuantity = $productsAndQuantities[(string)$line->productId()];

            $deliveryNote->deliverGoods(
                $line->productId(),
                $receivedQuantity,
                $balance->getQuantityInStock()
            );
        }

        $this->deliveryNoteRepository->save($deliveryNote);

        return $deliveryNote;
    }
}
