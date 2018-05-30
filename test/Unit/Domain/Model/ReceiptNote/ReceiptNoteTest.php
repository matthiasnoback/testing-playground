<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\PurchaseOrderId;
use PHPUnit\Framework\TestCase;

final class ReceiptNoteTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created_for_a_given_purchase_order(): void
    {
        $receiptNoteId = $this->someReceiptNoteId();
        $purchaseOrderId = $this->somePurchaseOrderId();

        $receiptNote = ReceiptNote::create($receiptNoteId, $purchaseOrderId);

        self::assertEquals($receiptNote->receiptNoteId(), $receiptNoteId);
        self::assertEquals($receiptNote->purchaseOrderId(), $purchaseOrderId);

        self::assertEquals(
            [
                new ReceiptNoteCreated($receiptNoteId)
            ],
            $receiptNote->recordedEvents()
        );
    }

    /**
     * @test
     */
    public function it_can_receive_goods_for_a_given_product_id(): void
    {
        $receiptNoteId = $this->someReceiptNoteId();
        $purchaseOrderId = $this->somePurchaseOrderId();
        $receiptNote = ReceiptNote::create($receiptNoteId, $purchaseOrderId);
        // clear recorded events
        $receiptNote->recordedEvents();

        $productId = $this->someProductId();
        $quantity = new ReceiptQuantity(10);
        $receiptNote->receive($productId, $quantity);

        self::assertCount(1, $receiptNote->lines());

        self::assertEquals([
            new GoodsReceived($receiptNoteId, $purchaseOrderId, $productId, $quantity)
        ], $receiptNote->recordedEvents());
    }

    private function someReceiptNoteId(): ReceiptNoteId
    {
        return ReceiptNoteId::fromString('42dc4bd0-891c-4a50-87f9-1b7adc7758a0');
    }

    private function somePurchaseOrderId(): PurchaseOrderId
    {
        return PurchaseOrderId::fromString('ce83aeb3-0f17-4e10-b512-aaa976359757');
    }

    private function someProductId(): ProductId
    {
        return ProductId::fromString('163740de-3fbe-4ff0-9866-5cdd58a8151a');
    }
}
