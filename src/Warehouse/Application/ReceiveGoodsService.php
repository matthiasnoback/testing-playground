<?php
declare(strict_types=1);

namespace Warehouse\Application;

use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\Product\ProductRepository;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNote;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNoteId;
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

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        PurchaseOrderRepository $purchaseOrderRepository,
        ReceiptNoteRepository $receiptNoteRepository,
        ProductRepository $productRepository
    ) {
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->receiptNoteRepository = $receiptNoteRepository;
        $this->productRepository = $productRepository;
    }

    public function receive(string $purchaseOrderId, array $productsAndQuantities): ReceiptNoteId
    {
        $purchaseOrderIdObject = PurchaseOrderId::fromString($purchaseOrderId);

        $purchaseOrder = $this->purchaseOrderRepository->getById($purchaseOrderIdObject);

        $receiptNoteId = $this->receiptNoteRepository->nextIdentity();

        $receiptNote = new ReceiptNote($receiptNoteId, $purchaseOrder->purchaseOrderId());

        foreach ($productsAndQuantities as $productId => $quantity) {
            $product = $this->productRepository->getById(ProductId::fromString($productId));

            $receiptNote->addLine($product->productId(), $quantity);
        }

        $this->receiptNoteRepository->save($receiptNote);

        return $receiptNoteId;
    }
}
