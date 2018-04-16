<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\Product\Product;
use Domain\Model\Supplier\Supplier;
use InvalidArgumentException;
use LogicException;

final class PurchaseOrder
{
    /**
     * @var Supplier
     */
    private $supplier;

    /**
     * @var Line[]
     */
    private $lines = [];

    private function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public static function create(Supplier $supplier): PurchaseOrder
    {
        return new self($supplier);
    }

    public function addLine(Product $product, float $quantity): void
    {
        foreach ($this->lines as $line) {
            if ($line->product()->equals($product)) {
                throw new InvalidArgumentException('You cannot add the same product twice.');
            }
        }

        $this->lines[] = new Line($product, $quantity);
    }

    public function place(): void
    {
        if (\count($this->lines) < 1) {
            throw new LogicException('To place a purchase order, it has to have at least one line.');
        }
    }

    public function supplier(): Supplier
    {
        return $this->supplier;
    }

    /**
     * @return Line[]
     */
    public function lines(): array
    {
        return $this->lines;
    }
}
