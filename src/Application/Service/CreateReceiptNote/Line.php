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
     * @var int
     */
    public $quantity;
}
