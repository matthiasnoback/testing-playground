<?php
declare(strict_types=1);

namespace Application;

use Application\ReadModel\BalanceRepository;
use Domain\Model\ReceiptNote\GoodsReceived;

final class UpdateStockBalance
{
    /**
     * @var BalanceRepository
     */
    private $balanceRepository;

    public function __construct(BalanceRepository $balanceRepository)
    {
        $this->balanceRepository = $balanceRepository;
    }

    public function whenGoodsReceived(GoodsReceived $goodsReceived): void
    {
        $currentBalance = $this->balanceRepository->getBalanceFor($goodsReceived->productId());

        $updatedBalance = $currentBalance->processReceipt($goodsReceived->quantity());

        $this->balanceRepository->save($updatedBalance);
    }
}
