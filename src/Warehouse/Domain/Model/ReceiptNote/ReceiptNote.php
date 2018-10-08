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

namespace Warehouse\Domain\Model\ReceiptNote;

use Common\Aggregate;
use Common\AggregateId;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;

/**
 * @author Pierre Allard <pierre.allard@akeneo.com>
 */
class ReceiptNote extends Aggregate
{
    /**
     * @var ReceiptNoteId
     */
    private $receiptNoteId;
    /**
     * @var PurchaseOrderId
     */
    private $purchaseOrderId;

    private $lines;

    public function __construct(
        ReceiptNoteId $receiptNoteId,
        PurchaseOrderId $purchaseOrderId
    ) {
        $this->receiptNoteId = $receiptNoteId;
        $this->purchaseOrderId = $purchaseOrderId;
        $this->lines = [];

        $this->recordThat(new ReceiptNoteCreated($receiptNoteId, $purchaseOrderId));
    }

    public function addLine(ProductId $productId, $quantity)
    {
        $this->lines[] = new ReceiptNoteLine($productId, $quantity);

        $this->recordThat(new GoodsReceived($productId, $quantity));
    }

    public function id(): AggregateId
    {
        return $this->receiptNoteId;
    }
}
