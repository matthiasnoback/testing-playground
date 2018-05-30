<?php
declare(strict_types=1);

namespace Application\ReadModel;

final class StockLevel
{
    /**
     * @var int
     */
    private $quantityInStock;

    private function __construct(int $quantityInStock)
    {
        $this->quantityInStock = $quantityInStock;
    }

    public static function initial(): StockLevel
    {
        return new StockLevel(0);
    }

    public function add(int $quantity): StockLevel
    {
        return new self(
            $this->quantityInStock + $quantity
        );
    }

    public function subtract(int $quantity): StockLevel
    {
        return new self(
            $this->quantityInStock - $quantity
        );
    }

    public function asInt(): int
    {
        return $this->quantityInStock;
    }
}
