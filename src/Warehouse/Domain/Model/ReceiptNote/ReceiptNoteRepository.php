<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\ReceiptNote;

interface ReceiptNoteRepository
{
    public function save(ReceiptNote $aggregate): void;

    public function getById(ReceiptNoteId $aggregateId): ReceiptNote;

    public function nextIdentity(): ReceiptNoteId;
}
