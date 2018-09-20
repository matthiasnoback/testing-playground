<?php
declare(strict_types=1);

namespace Warehouse\Infrastructure;

use Common\AggregateNotFound;
use Common\AggregateRepository;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNote;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNoteId;
use Warehouse\Domain\Model\DeliveryNote\DeliveryNoteRepository;

final class DeliveryNoteAggregateRepository extends AggregateRepository implements DeliveryNoteRepository
{
    public function save(DeliveryNote $aggregate): void
    {
        $this->store($aggregate);
    }

    public function getById(DeliveryNoteId $aggregateId): DeliveryNote
    {
        $aggregate = $this->load((string)$aggregateId);

        if (!$aggregate instanceof DeliveryNote) {
            throw AggregateNotFound::with(DeliveryNote::class, (string)$aggregateId);
        }

        return $aggregate;
    }

    public function nextIdentity(): DeliveryNoteId
    {
        return DeliveryNoteId::fromString($this->generateUuid());
    }
}
