<?php
declare(strict_types=1);

namespace Application\ReadModel;

use Domain\Model\Product\ProductId;
use Domain\Model\ReceiptNote\ReceiptQuantity;
use PHPUnit\Framework\TestCase;

final class BalanceTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_set_up_from_scratch(): void
    {
        $productId = $this->someProductId();
        $balance = Balance::fromScratch($productId);

        self::assertEquals($productId, $balance->productId());
        self::assertEquals(0, $balance->stockLevel()->asInt());
    }

    /**
     * @test
     */
    public function it_can_process_a_receipt(): void
    {
        $productId = $this->someProductId();
        $balance = Balance::fromScratch($productId);

        $balance = $balance->processReceipt(new ReceiptQuantity(10));

        self::assertEquals($productId, $balance->productId());
        self::assertEquals(10, $balance->stockLevel()->asInt());
    }

    /**
     * @test
     */
    public function it_can_process_multiple_receipts(): void
    {
        $productId = $this->someProductId();
        $balance = Balance::fromScratch($productId);

        $balance = $balance->processReceipt(new ReceiptQuantity(10));
        $balance = $balance->processReceipt(new ReceiptQuantity(5));

        self::assertEquals($productId, $balance->productId());
        self::assertEquals(15, $balance->stockLevel()->asInt());
    }

    private function someProductId(): ProductId
    {
        return ProductId::fromString('0d09b92f-17a9-44a3-855c-64ad0e5f31d4');
    }
}
