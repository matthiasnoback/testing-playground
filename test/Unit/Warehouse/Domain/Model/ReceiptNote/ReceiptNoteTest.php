<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\ReceiptNote;

use PHPUnit\Framework\TestCase;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;

final class ReceiptNoteTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created_for_a_purchase_order(): void
    {
        $receiptNoteId = ReceiptNoteId::fromString('45b9b2dd-ac08-4af5-aa92-b405155cb5f1');
        $purchaseOrderId = PurchaseOrderId::fromString('5d4c7aa6-ed8c-4201-b880-3995b030af39');
        $receiptNote = ReceiptNote::create(
            $receiptNoteId,
            $purchaseOrderId
        );

        self::assertEquals($receiptNoteId, $receiptNote->id());
        self::assertEquals($purchaseOrderId, $receiptNote->purchaseOrderId());
    }

    /**
     * @test
     */
    public function you_can_receive_goods_on_it(): void
    {
        $receiptNote = ReceiptNote::create(
            ReceiptNoteId::fromString('45b9b2dd-ac08-4af5-aa92-b405155cb5f1'),
            PurchaseOrderId::fromString('5d4c7aa6-ed8c-4201-b880-3995b030af39')
        );

        $receiptNote->receiveGoods(ProductId::fromString('a9cc4419-a382-4ca5-861f-c5bfc34c6880'), 10);

        self::assertCount(1, $receiptNote->receivedGoods());
    }
}
