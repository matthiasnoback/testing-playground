<?php
declare(strict_types=1);

namespace Warehouse\Application;

use RuntimeException;
use Warehouse\Application\ReadModel\BalanceRepository;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNote;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNoteId;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNoteRepository;
use Warehouse\Domain\Model\Product\ProductRepository;
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
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var BalanceRepository
     */
    private $balanceRepository;

    public function __construct(
        SalesOrderRepository $salesOrderRepository,
        DeliveryNoteRepository $deliverNoteRepository,
        ProductRepository $productRepository,
        BalanceRepository $balanceRepository
    ) {
        $this->salesOrderRepository = $salesOrderRepository;
        $this->deliveryNoteRepository = $deliverNoteRepository;
        $this->productRepository = $productRepository;
        $this->balanceRepository = $balanceRepository;
    }

    public function deliver(string $salesOrderId): DeliveryNoteId
    {
        $salesOrder = $this->salesOrderRepository->getById(SalesOrderId::fromString($salesOrderId));

        $deliveryNoteId = $this->deliveryNoteRepository->nextIdentity();

        $deliveryNote = new DeliveryNote($deliveryNoteId, $salesOrder->salesOrderId());

        foreach ($salesOrder->lines() as $line) {
            $product = $this->productRepository->getById($line->productId());

            if ($this->balanceRepository->getById($line->productId())->quantityInStock() < $line->quantity()) {
                throw new RuntimeException('This line can not be delivered');
            }

            $deliveryNote->addLine($product->productId(), $line->quantity());
        }

        $this->deliveryNoteRepository->save($deliveryNote);

        return $deliveryNoteId;
    }
}
