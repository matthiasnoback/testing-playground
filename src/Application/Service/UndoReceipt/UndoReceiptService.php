<?php
declare(strict_types=1);

namespace Application\Service\UndoReceipt;

use Domain\Model\ReceiptNote\ReceiptNoteId;
use Domain\Model\ReceiptNote\ReceiptNoteRepository;

final class UndoReceiptService
{
    /**
     * @var ReceiptNoteRepository
     */
    private $receiptNoteRepository;

    public function __construct(ReceiptNoteRepository $receiptNoteRepository)
    {
        $this->receiptNoteRepository = $receiptNoteRepository;
    }

    public function undo(string $receiptNoteId): void
    {
        $receiptNote = $this->receiptNoteRepository->getById(
            ReceiptNoteId::fromString($receiptNoteId)
        );

        $receiptNote->undo();

        $this->receiptNoteRepository->save($receiptNote);
    }
}
