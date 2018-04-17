<?php
declare(strict_types=1);

namespace Domain\Model\ReceiptNote;

use function Common\CommandLine\line;
use function Common\CommandLine\make_green;

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

    public function __toString()
    {
        return line(
            make_green('Receipt note created'),
            sprintf(': %s', $this->receiptNoteId)
        );
    }
}
