<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\SalesOrder;

use PHPUnit\Framework\TestCase;
use Warehouse\Domain\Model\Product\ProductId;

final class SalesOrderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created(): void
    {
        $salesOrderId = SalesOrderId::fromString('5d4c7aa6-ed8c-4201-b880-3995b030af39');
        $salesOrder = SalesOrder::create($salesOrderId);

        $line1ProductId = ProductId::fromString('a9cc4419-a382-4ca5-861f-c5bfc34c6880');
        $line1Quantity = 10;
        $salesOrder->addLine($line1ProductId, $line1Quantity);

        $line2ProductId = ProductId::fromString('5b40609f-5e4b-43fa-9c14-f9f59a056d2f');
        $line2Quantity = 5;
        $salesOrder->addLine($line2ProductId, $line2Quantity);

        self::assertEquals($salesOrderId, $salesOrder->id());
        self::assertCount(2, $salesOrder->lines());
        self::assertEquals($line1ProductId, $salesOrder->lines()[(string)$line1ProductId]->productId());
        self::assertEquals($line1Quantity, $salesOrder->lines()[(string)$line1ProductId]->quantity());
        self::assertEquals($line2ProductId, $salesOrder->lines()[(string)$line2ProductId]->productId());
        self::assertEquals($line2Quantity, $salesOrder->lines()[(string)$line2ProductId]->quantity());
    }
}
