<?php
declare(strict_types=1);

namespace Application\ReadModel;

final class StockLevel
{
    /**
     * @var float
     */
    private $quantityInStock;

    private function __construct(float $quantityInStock)
    {
        $this->quantityInStock = $quantityInStock;
    }

    public static function initial(): StockLevel
    {
        return new StockLevel(0.0);
    }

    public function add(float $quantity): StockLevel
    {
        return new self(
            $this->quantityInStock + $quantity
        );
    }

    public function asFloat(): float
    {
        return $this->quantityInStock;
    }
}
