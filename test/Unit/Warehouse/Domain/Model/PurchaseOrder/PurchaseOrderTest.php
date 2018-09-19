<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\PurchaseOrder;

use PHPUnit\Framework\TestCase;
use Warehouse\Domain\Model\Product\ProductId;

final class PurchaseOrderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $purchaseOrderId = PurchaseOrderId::fromString('5d4c7aa6-ed8c-4201-b880-3995b030af39');
        $purchaseOrder = PurchaseOrder::create($purchaseOrderId);

        $line1ProductId = ProductId::fromString('a9cc4419-a382-4ca5-861f-c5bfc34c6880');
        $line1Quantity = 10;
        $purchaseOrder->addLine($line1ProductId, $line1Quantity);

        $line2ProductId = ProductId::fromString('5b40609f-5e4b-43fa-9c14-f9f59a056d2f');
        $line2Quantity = 5;
        $purchaseOrder->addLine($line2ProductId, $line2Quantity);

        self::assertEquals($purchaseOrderId, $purchaseOrder->id());
        self::assertCount(2, $purchaseOrder->lines());
        self::assertEquals($line1ProductId, $purchaseOrder->lines()[0]->productId());
        self::assertEquals($line1Quantity, $purchaseOrder->lines()[0]->quantity());
        self::assertEquals($line2ProductId, $purchaseOrder->lines()[1]->productId());
        self::assertEquals($line2Quantity, $purchaseOrder->lines()[1]->quantity());
    }
}
