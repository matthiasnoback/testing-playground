<?php
declare(strict_types=1);

namespace Warehouse\Application;

use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNote;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNoteRepository;

final class ReceiveGoodsService
{
    /**
     * @var PurchaseOrderRepository
     */
    private $purchaseOrderRepository;
    /**
     * @var ReceiptNoteRepository
     */
    private $receiptNoteRepository;

    public function __construct(
        PurchaseOrderRepository $purchaseOrderRepository,
        ReceiptNoteRepository $receiptNoteRepository
    ) {
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->receiptNoteRepository = $receiptNoteRepository;
    }

    public function receive(string $purchaseOrderId, array $productsAndQuantities): ReceiptNote
    {
        $purchaseOrder = $this->purchaseOrderRepository->getById(
            PurchaseOrderId::fromString($purchaseOrderId)
        );

        $receiptNote = ReceiptNote::create(
            $this->receiptNoteRepository->nextIdentity(),
            $purchaseOrder->purchaseOrderId()
        );

        foreach ($purchaseOrder->lines() as $line) {
            if (!isset($productsAndQuantities[(string)$line->productId()])) {
                continue;
            }

            $receivedQuantity = $productsAndQuantities[(string)$line->productId()];
            $receiptNote->receiveGoods(
                $line->productId(),
                $receivedQuantity
            );
        }

        $this->receiptNoteRepository->save($receiptNote);

        return $receiptNote;
    }
}
