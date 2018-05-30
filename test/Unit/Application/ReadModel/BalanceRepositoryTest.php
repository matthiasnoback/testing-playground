<?php
declare(strict_types=1);

namespace Application\ReadModel;

use Common\EventDispatcher\EventDispatcher;
use Domain\Model\Product\ProductId;
use Domain\Model\ReceiptNote\ReceiptQuantity;
use PHPUnit\Framework\TestCase;

/**
 * Psst... This is usually an integration test (when there's actual infrastructure involved).
 */
final class BalanceRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_balance_from_scratch_if_it_does_not_exist_yet(): void
    {
        $balanceRepository = new BalanceRepository(new EventDispatcher());

        $productId = $this->someProductId();
        $balance = $balanceRepository->getBalanceFor($productId);

        self::assertEquals($productId, $balance->productId());
        self::assertEquals(0, $balance->stockLevel()->asInt());
    }

    /**
     * @test
     */
    public function it_saves_an_updated_balance(): void
    {
        $balanceRepository = new BalanceRepository(new EventDispatcher());
        $productId = $this->someProductId();
        $balance = $balanceRepository->getBalanceFor($productId);
        $balance = $balance->processReceipt(new ReceiptQuantity(10));

        $balanceRepository->save($balance);

        $retrievedBalance = $balanceRepository->getBalanceFor($productId);

        self::assertEquals($productId, $retrievedBalance->productId());
        self::assertEquals(10, $retrievedBalance->stockLevel()->asInt());
    }

    private function someProductId(): ProductId
    {
        return ProductId::fromString('0d09b92f-17a9-44a3-855c-64ad0e5f31d4');
    }
}
