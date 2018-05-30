<?php
declare(strict_types=1);

namespace Application\Service\CreateReceiptNote;

use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\PurchaseOrderId;
use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\ReceiptNote\ReceiptNote;
use Domain\Model\ReceiptNote\ReceiptNoteRepository;
use Domain\Model\ReceiptNote\ReceiptQuantity;

final class CreateReceiptNoteService
{
    /**
     * @var PurchaseOrderRepository
     */
    private $purchaseOrderRepository;

    /**
     * @var ReceiptNoteRepository
     */
    private $receiptNoteRepository;

    public function __construct(PurchaseOrderRepository $purchaseOrderRepository, ReceiptNoteRepository $receiptNoteRepository)
    {
        $this->receiptNoteRepository = $receiptNoteRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
    }

    public function create(CreateReceiptNote $dto): ReceiptNote
    {
        $purchaseOrderId = PurchaseOrderId::fromString($dto->purchaseOrderId);
        $purchaseOrder = $this->purchaseOrderRepository->getById($purchaseOrderId);

        $receiptNote = ReceiptNote::create(
            $this->receiptNoteRepository->nextIdentity(),
            $purchaseOrderId
        );

        foreach ($dto->lines as $line) {
            $productId = ProductId::fromString($line->productId);
            if ($purchaseOrder->lineForProduct($productId)) {
                $receiptNote->receive($productId, new ReceiptQuantity($line->quantity));
            }
        }

        $this->receiptNoteRepository->save($receiptNote);

        return $receiptNote;
    }
}
