<?php

namespace Warehouse\Domain\Model\ReceiptNote;

use RuntimeException;

interface ReceiptNoteRepository
{
    public function nextIdentity(): ReceiptNoteId;

    public function save(ReceiptNote $receiptNote): void;

    /**
     * @throws RuntimeException
     */
    public function getById(ReceiptNoteId $aggregateId): ReceiptNote;
}
