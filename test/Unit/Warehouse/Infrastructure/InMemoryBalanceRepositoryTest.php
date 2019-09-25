<?php

namespace Warehouse\Infrastructure;

use PHPUnit\Framework\TestCase;
use Warehouse\Application\ReadModel\Balance;
use Warehouse\Domain\Model\Product\ProductId;

final class InMemoryBalanceRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_save_and_retrieve_a_balance_read_model(): void
    {
        $repository = new InMemoryBalanceRepository();
        $productId = ProductId::fromString('33825c58-b3c6-4d99-8e22-c60c89c874b6');

        $balance = new Balance($productId);
        $repository->save($balance);

        $fromRepository = $repository->getById($productId);
        self::assertEquals($balance, $fromRepository);
    }
}
