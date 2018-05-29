<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Ramsey\Uuid\Uuid;

final class ReceiptNoteRepository extends AggregateRepository
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
        return ReceiptNoteId::fromString(Uuid::uuid4()->toString());
    }
}
