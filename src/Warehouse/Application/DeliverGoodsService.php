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

use Warehouse\Domain\Model\DeliveryNote\DeliveryNote;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNoteRepository;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\Product\ProductRepository;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderRepository;

/**
 * @author Pierre Allard <pierre.allard@akeneo.com>
 */
class DeliverGoodsService
{
    /** @var SalesOrderRepository */
    private $salesOrderRepository;

    private $deliverNoteRepository;

    private $productRepository;

    public function __construct(
        SalesOrderRepository $salesOrderRepository,
        DeliveryNoteRepository $deliverNoteRepository,
        ProductRepository $productRepository
    ) {
        $this->salesOrderRepository = $salesOrderRepository;
        $this->deliverNoteRepository = $deliverNoteRepository;
        $this->productRepository = $productRepository;
    }

    public function deliver(string $salesOrderId, array $productsAndQuantities): DeliveryNote
    {
        $salesOrder = $this->salesOrderRepository->getById(SalesOrderId::fromString($salesOrderId));

        $deliverNoteId = $this->deliverNoteRepository->nextIdentity();

        $deliverNote = new DeliveryNote($deliverNoteId, $salesOrder->salesOrderId());

        foreach ($productsAndQuantities as $productId => $quantity) {
            $product = $this->productRepository->getById(ProductId::fromString($productId));

            $deliverNote->addLine($product->productId(), $quantity);
        }

        $this->deliverNoteRepository->save($deliverNote);

        return $deliverNote;
    }
}
