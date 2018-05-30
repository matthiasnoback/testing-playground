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
     * @var float
     */
    public $quantity;
}
