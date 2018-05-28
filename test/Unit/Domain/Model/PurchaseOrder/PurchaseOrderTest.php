<?php
declare(strict_types=1);

namespace Domain\Model\PurchaseOrder;

use Domain\Model\Product\ProductId;
use Domain\Model\ReceiptNote\ReceiptQuantity;
use Domain\Model\Supplier\SupplierId;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

final class PurchaseOrderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_placed_for_a_certain_supplier(): void
    {
        $supplierId = $this->someSupplierId();

        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $supplierId);

        self::assertInstanceOf(PurchaseOrder::class, $purchaseOrder);
        self::assertEquals($supplierId, $purchaseOrder->supplierId());
    }

    /**
     * @test
     */
    public function you_can_add_a_certain_quantity_of_a_stock_product_to_it(): void
    {
        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $this->someSupplierId());

        $purchaseOrder->addLine($this->someProductId(), $someQuantity = new OrderedQuantity(10.0));

        $this->assertCount(1, $purchaseOrder->lines());
    }

    /**
     * @test
     */
    public function you_can_not_order_a_negative_quantity(): void
    {
        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $this->someSupplierId());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('larger than 0');

        $purchaseOrder->addLine($this->someProductId(), $aNegativeQuantity = new OrderedQuantity(-5.0));
    }

    /**
     * @test
     */
    public function you_can_not_order_a_quantity_of_0(): void
    {
        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $this->someSupplierId());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('larger than 0');

        $purchaseOrder->addLine($this->someProductId(), new OrderedQuantity(0.0));
    }

    /**
     * @test
     */
    public function you_can_not_order_the_same_product_twice(): void
    {
        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $this->someSupplierId());

        $purchaseOrder->addLine($this->someProductId(), new OrderedQuantity(10.0));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('same product');

        $purchaseOrder->addLine($this->someProductId(), new OrderedQuantity(5.0));
    }

    /**
     * @test
     */
    public function added_lines_will_be_numbered(): void
    {
        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $this->someSupplierId());

        $productOfLine1 = $this->someProductId();
        $productOfLine2 = $this->someOtherProductId();
        $purchaseOrder->addLine($productOfLine1, $this->someQuantity());
        $purchaseOrder->addLine($productOfLine2, $this->someQuantity());

        self::assertEquals(1, $purchaseOrder->lineForProduct($productOfLine1)->lineNumber());
        self::assertEquals(2, $purchaseOrder->lineForProduct($productOfLine2)->lineNumber());
    }

    /**
     * @test
     */
    public function you_have_to_at_least_order_one_thing(): void
    {
        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $this->someSupplierId());

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('at least one line');

        $purchaseOrder->place();
    }

    /**
     * @test
     */
    public function it_can_be_placed(): void
    {
        $purchaseOrderId = $this->somePurchaseOrderId();
        $purchaseOrder = PurchaseOrder::create($purchaseOrderId, $this->someSupplierId());
        $purchaseOrder->addLine($this->someProductId(), new OrderedQuantity(10.0));
        $purchaseOrder->place();

        self::assertEquals(
             [
                 new PurchaseOrderPlaced($purchaseOrderId)
             ],
             $purchaseOrder->recordedEvents()
        );
        self::assertFalse($purchaseOrder->isFullyDelivered());
    }

    /**
     * @test
     */
    public function you_can_not_place_the_same_purchase_order_again(): void
    {
        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $this->someSupplierId());
        $purchaseOrder->addLine($this->someProductId(), new OrderedQuantity(10.0));
        $purchaseOrder->place();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('already been placed');

        $purchaseOrder->place();
    }

    /**
     * @test
     */
    public function after_processing_receipts_for_all_ordered_products_it_will_be_fully_delivered(): void
    {
        $purchaseOrderId = $this->somePurchaseOrderId();
        $purchaseOrder = PurchaseOrder::create($purchaseOrderId, $this->someSupplierId());
        $productId = $this->someProductId();
        $orderedQuantity = new OrderedQuantity(10.0);
        $purchaseOrder->addLine($productId, $orderedQuantity);
        $purchaseOrder->place();
        // clear recorded events
        $purchaseOrder->recordedEvents();

        $purchaseOrder->processReceipt($productId, new ReceiptQuantity($orderedQuantity->asFloat()));

        self::assertTrue($purchaseOrder->isFullyDelivered());
        self::assertEquals(
            [
                new PurchaseOrderCompleted($purchaseOrderId)
            ],
            $purchaseOrder->recordedEvents()
        );
    }

    /**
     * @test
     */
    public function after_processing_partial_receipts_for_ordered_products_it_will_not_be_fully_delivered(): void
    {
        $purchaseOrder = PurchaseOrder::create($this->somePurchaseOrderId(), $this->someSupplierId());
        $productId = $this->someProductId();
        $orderedQuantity = new OrderedQuantity(10.0);
        $purchaseOrder->addLine($productId, $orderedQuantity);
        $purchaseOrder->place();

        $purchaseOrder->processReceipt($productId, new ReceiptQuantity($lessThanTheOrderedQuantity = 5.0));

        self::assertFalse($purchaseOrder->isFullyDelivered());
    }

    private function someSupplierId(): SupplierId
    {
        return SupplierId::fromString('1900091c-7bb6-4e43-ac4e-308a4853686b');
    }

    private function someProductId(): ProductId
    {
        return ProductId::fromString('a5aa7b51-7aa9-4344-82ea-8cd9ba8b3655');
    }

    private function someOtherProductId(): ProductId
    {
        return ProductId::fromString('75bf88e4-a13e-415c-b56c-8c9cdc41f3c8');
    }

    private function somePurchaseOrderId(): PurchaseOrderId
    {
        return PurchaseOrderId::fromString('99ab0293-2fd1-4a5a-859d-e12bd91d6955');
    }

    private function someQuantity(): OrderedQuantity
    {
        return new OrderedQuantity(10);
    }
}
