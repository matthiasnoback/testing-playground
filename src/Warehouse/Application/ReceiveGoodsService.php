<?php

declare(strict_types=1);

/*
 * This file is part of the Akeneo PIM Enterprise Edition.
 *
 * (c) 2018 Akeneo SAS (http://www.akeneo.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Warehouse\Application;

use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\Product\ProductRepository;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNote;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNoteRepository;

/**
 * @author Pierre Allard <pierre.allard@akeneo.com>
 */
class ReceiveGoodsService
{
    /** @var PurchaseOrderRepository */
    private $purchaseOrderRepository;

    private $receiptNoteRepository;

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

    public function receive(string $purchaseOrderId, array $productsAndQuantities): ReceiptNote
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

        return $receiptNote;
    }
}
