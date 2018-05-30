<?php
declare(strict_types=1);

namespace Application\Service\CreateReceiptNote;

final class CreateReceiptNote
{
    /**
     * @var string
     */
    public $purchaseOrderId;

    /**
     * @var array|Line[]
     */
    public $lines = [];
}
