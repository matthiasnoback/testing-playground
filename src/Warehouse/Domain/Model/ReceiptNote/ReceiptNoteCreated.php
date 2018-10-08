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
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;

/**
 * @author Julian Prud'homme <julian.prudhomme@akeneo.com>
 */
class ReceiptNoteCreated
{
    /** @var ReceiptNoteId */
    private $receiptNoteId;
    /** @var PurchaseOrderId */
    private $purchaseOrderId;

    public function __construct(ReceiptNoteId $receiptNoteId, PurchaseOrderId $purchaseOrderId)
    {
        $this->receiptNoteId = $receiptNoteId;
        $this->purchaseOrderId = $purchaseOrderId;
    }
}
