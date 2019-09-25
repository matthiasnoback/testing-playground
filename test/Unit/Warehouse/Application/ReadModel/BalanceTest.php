<?php

namespace Warehouse\Application\ReadModel;

use PHPUnit\Framework\TestCase;
use Warehouse\Domain\Model\Product\ProductId;

final class BalanceTest extends TestCase
{
    /**
     * @test
     */
    public function initially_the_quantity_in_stock_is_zero(): void
    {
        $balance = new Balance($this->productId());

        self::assertEquals(0, $balance->quantityInStock());
    }

    /**
     * @test
     */
    public function the_quantity_in_stock_can_increase(): void
    {
        $balance = new Balance($this->productId());
        $balance->increase(10);
        $balance->increase(5);
        self::assertEquals(15, $balance->quantityInStock());
    }

    /**
     * @test
     */
    public function the_quantity_in_stock_can_decrease(): void
    {
        $balance = new Balance($this->productId());
        $balance->increase(10);
        $balance->decrease(4);
        self::assertEquals(6, $balance->quantityInStock());
    }

    private function productId(): ProductId
    {
        return ProductId::fromString('33825c58-b3c6-4d99-8e22-c60c89c874b6');
    }
}
