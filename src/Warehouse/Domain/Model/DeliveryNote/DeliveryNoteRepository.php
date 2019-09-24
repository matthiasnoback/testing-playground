<?php

namespace Warehouse\Domain\Model\DeliveryNote;

interface DeliveryNoteRepository
{
    public function nextIdentity(): DeliveryNoteId;

    public function save(DeliveryNote $deliveryNote): void;
}
