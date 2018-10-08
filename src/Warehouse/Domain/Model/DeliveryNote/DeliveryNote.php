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

namespace Warehouse\Domain\Model\DeliveryNote;

use Common\Aggregate;
use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

/**
 * @author Pierre Allard <pierre.allard@akeneo.com>
 */
class DeliveryNote extends Aggregate
{
    /**
     * @var DeliveryNoteId
     */
    private $deliveryNoteId;
    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    private $lines;

    public function __construct(
        DeliveryNoteId $deliveryNoteId,
        SalesOrderId $salesOrderId
    ) {
        $this->deliveryNoteId = $deliveryNoteId;
        $this->salesOrderId = $salesOrderId;
        $this->lines = [];

        $this->recordThat(new DeliveryNoteCreated($deliveryNoteId, $salesOrderId));
    }

    public function addLine(ProductId $productId, $quantity)
    {
        $this->lines[] = new DeliveryNoteLine($productId, $quantity);

        $this->recordThat(new GoodsDelivered($productId, $quantity));
    }

    public function id(): AggregateId
    {
        return $this->deliveryNoteId;
    }
}
