<?php
/**
 * Created by PhpStorm.
 * User: pierallard
 * Date: 08/10/18
 * Time: 11:34
 */


namespace Warehouse\Domain\Model\ReceiptNote;


interface ReceiptNoteRepository
{
    public function nextIdentity();

    public function save(ReceiptNote $receiptNote);
}
