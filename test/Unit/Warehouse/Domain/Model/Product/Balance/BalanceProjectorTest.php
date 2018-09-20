<?php

namespace Warehouse\Domain\Model\Product\Balance;

use PHPUnit\Framework\TestCase;
use Warehouse\Domain\Model\DeliveryNote\GoodsDelivered;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\Product\ProductWasCreated;
use Warehouse\Domain\Model\ReceiptNote\GoodsReceived;

class BalanceProjectorTest extends TestCase
{
    /**
     * @var BalanceProjector
     */
    private $balanceProjector;

    /**
     * @var BalanceRepository
     */
    private $balanceRepository;

    /**
     * @test
     */
    public function shouldProjectProductWasCreated(): void
    {
        $productId = ProductId::fromString('620F037B-5FD5-4678-A092-75CE7451E1FB');
        ($this->balanceProjector)(new ProductWasCreated($productId));

        $this->assertEquals(0, $this->balanceRepository->getByProductId($productId)->getQuantityInStock());
    }

    /**
     * @test
     */
    public function shouldProjectProductWasReceived(): void
    {
        $productId = ProductId::fromString('620F037B-5FD5-4678-A092-75CE7451E1FB');
        ($this->balanceProjector)(new ProductWasCreated($productId));
        ($this->balanceProjector)(new GoodsReceived($productId, 3));

        $this->assertEquals(3, $this->balanceRepository->getByProductId($productId)->getQuantityInStock());
    }

    /**
     * @test
     */
    public function shouldProjectProductWasDelivered(): void
    {
        $productId = ProductId::fromString('620F037B-5FD5-4678-A092-75CE7451E1FB');
        ($this->balanceProjector)(new ProductWasCreated($productId));
        ($this->balanceProjector)(new GoodsReceived($productId, 7));
        ($this->balanceProjector)(new GoodsDelivered($productId, 3));

        $this->assertEquals(4, $this->balanceRepository->getByProductId($productId)->getQuantityInStock());
    }

    protected function setUp()
    {
        $this->balanceRepository = new InMemoryBalanceRepository();
        $this->balanceProjector = new BalanceProjector(
            $this->balanceRepository
        );
    }
}
