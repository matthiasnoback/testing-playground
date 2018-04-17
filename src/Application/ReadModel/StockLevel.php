<?php
declare(strict_types=1);

namespace Application\ReadModel;

use Domain\Model\PurchaseOrder\Quantity;

final class StockLevel
{
    /**
     * @var float
     */
    private $quantityInStock;

    public function __construct(float $quantityInStock)
    {
        $this->quantityInStock = $quantityInStock;
    }

    public static function initial()
    {
        return new StockLevel(0.0);
    }

    public function add(Quantity $quantity): StockLevel
    {
        return new self(
            $this->quantityInStock + $quantity->asFloat()
        );
    }

    public function asFloat(): float
    {
        return $this->quantityInStock;
    }
}
