<?php
declare(strict_types=1);

namespace Warehouse\Domain\Model\DeliveryNote;

interface DeliveryNoteRepository
{
    public function save(DeliveryNote $aggregate): void;

    public function getById(DeliveryNoteId $aggregateId): DeliveryNote;

    public function nextIdentity(): DeliveryNoteId;
}
