<?php
declare(strict_types=1);

namespace Application\Service\PlacePurchaseOrder;

final class Line
{
    /**
     * @var string
     */
    public $productId;

    /**
     * @var int
     */
    public $quantity;
}
