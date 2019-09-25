<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use PHPUnit\Framework\Assert;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Infrastructure\ServiceContainer;

final class FeatureContext implements Context
{
    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $this->serviceContainer = new ServiceContainer();
    }

    /**
     * @When I create a product :description
     * @Given a product :description
     */
    public function iCreateAProduct($description)
    {
        $this->productId = $this->serviceContainer->createProductService()->create($description);
    }

    /**
     * @Then the balance for this product should be :quantityInStock
     */
    public function theBalanceForThisProductShouldBe($quantityInStock)
    {
        $balance = $this->serviceContainer->balanceRepository()->getById(
            $this->productId
        );
        Assert::assertEquals($quantityInStock, $balance->quantityInStock());
    }

    /**
     * @When I receive :quantity items of this product
     * @Given I have received :quantity items of this product
     */
    public function iReceiveItemsOfThisProduct(string $quantity): void
    {
        $purchaseOrderId = $this->serviceContainer->createPurchaseOrderService()
            ->place(
                [
                    (string)$this->productId => (int)$quantity
                ]
            );

        $this->serviceContainer->receiveGoods()
            ->receive(
                (string)$purchaseOrderId,
                [
                    (string)$this->productId => (int)$quantity
                ]
            );
    }

    /**
     * @When I deliver :quantity items of this product
     */
    public function iDeliverItemsOfThisProduct($quantity)
    {
        $salesOrderId = $this->serviceContainer->placeSalesOrderService()
            ->place(
                [
                    (string)$this->productId => (int)$quantity
                ]
            );

        $this->serviceContainer->deliverGoodsService()
            ->deliver((string)$salesOrderId);
    }
}
