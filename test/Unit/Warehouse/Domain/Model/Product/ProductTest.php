<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\Product;

use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $productId = ProductId::fromString('a9cc4419-a382-4ca5-861f-c5bfc34c6880');
        $product = Product::create($productId);

        self::assertEquals($productId, $product->productId());
    }
}
