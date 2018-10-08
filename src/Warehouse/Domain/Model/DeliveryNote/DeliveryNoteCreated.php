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
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

/**
 * @author Julian Prud'homme <julian.prudhomme@akeneo.com>
 */
class DeliveryNoteCreated
{
    /** @var DeliveryNoteId */
    private $deliveryNoteId;
    /** @var SalesOrderId */
    private $salesOrderId;

    public function __construct(DeliveryNoteId $deliveryNoteId, SalesOrderId $salesOrderId)
    {
        $this->deliveryNoteId = $deliveryNoteId;
        $this->salesOrderId = $salesOrderId;
    }
}
