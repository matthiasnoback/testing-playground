<?php
declare(strict_types=1);

namespace Application\Service\PlacePurchaseOrder;

use Application\Service\PlacePurchaseOrder\Line;

final class PlacePurchaseOrder
{
    /**
     * @var string
     */
    public $supplierId;

    /**
     * @var array|Line[]
     */
    public $lines = [];
}
