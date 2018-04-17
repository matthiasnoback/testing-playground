<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

final class ReceiptNoteCreated
{
    /**
     * @var ReceiptNoteId
     */
    private $receiptNoteId;

    public function __construct(ReceiptNoteId $receiptNoteId)
    {
        $this->receiptNoteId = $receiptNoteId;
    }
}
