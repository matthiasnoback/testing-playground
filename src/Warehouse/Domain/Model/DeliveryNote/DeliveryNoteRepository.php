<?php
/**
 * Created by PhpStorm.
 * User: pierallard
 * Date: 08/10/18
 * Time: 11:34
 */


namespace Warehouse\Domain\Model\DeliveryNote;


interface DeliveryNoteRepository
{
    public function nextIdentity();

    public function save(DeliveryNote $deliveryNote);
}
