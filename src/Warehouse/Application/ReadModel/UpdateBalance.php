<?php
declare(strict_types=1);

namespace Warehouse\Application\ReadModel;

use Warehouse\Domain\Model\Product\ProductCreated;

final class UpdateBalance
{
    /**
     * @var BalanceRepository
     */
    private $balanceRepository;

    public function __construct(BalanceRepository $balanceRepository)
    {
        $this->balanceRepository = $balanceRepository;
    }

    public function whenProductCreated(ProductCreated $event): void
    {
        $balance = new Balance($event->productId());

        $this->balanceRepository->save($balance);
    }
}
