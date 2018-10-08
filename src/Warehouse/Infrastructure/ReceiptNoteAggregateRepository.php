<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNote;
use Warehouse\Domain\Model\ReceiptNote\ReceiptNoteId;

final class ReceiptNoteAggregateRepository extends AggregateRepository implements \Warehouse\Domain\Model\ReceiptNote\ReceiptNoteRepository
{
    public function save(ReceiptNote $aggregate): void
    {
        $this->store($aggregate);
    }

    public function getById(ReceiptNoteId $aggregateId): ReceiptNote
    {
        $aggregate = $this->load((string)$aggregateId);

        if (!$aggregate instanceof ReceiptNote) {
            throw AggregateNotFound::with(ReceiptNote::class, (string)$aggregateId);
        }

        return $aggregate;
    }

    public function nextIdentity(): ReceiptNoteId
    {
        return ReceiptNoteId::fromString($this->generateUuid());
    }
}
