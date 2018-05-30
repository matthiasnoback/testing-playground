<?php
declare(strict_types=1);

namespace Application\Service\CreateReceiptNote;

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
