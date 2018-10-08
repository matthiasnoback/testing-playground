<?php
declare(strict_types=1);

namespace Test\Acceptance;

use function assertEquals;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Warehouse\Domain\Model\Product\Product;
use Warehouse\Infrastructure\ServiceContainer;

final class FeatureContext implements Context
{
    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    /**
     * @var Product
     */
    private $product;

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
        $this->product = $this->serviceContainer->createProductService()->create($description);
    }

    /**
     * @Then the balance for this product should be :quantityInStock
     */
    public function theBalanceForThisProductShouldBe($quantityInStock)
    {
        $balance = $this->serviceContainer->balanceRepository()->getByProductId(
            $this->product->productId()
        );

        assertEquals($quantityInStock, $balance->quantityInStock());
    }

    /**
     * @When I receive :quantity items of this product
     * @Given I have received :quantity items of this product
     */
    public function iReceiveItemsOfThisProduct($quantity)
    {
        $purchaseOrder = $this->serviceContainer->placePurchaseOrderService()->place([
            (string)$this->product->productId() => (int)$quantity
        ]);
        $this->serviceContainer->receiveGoods()->receive(
            (string)$purchaseOrder->purchaseOrderId(),
            [
                (string)$this->product->productId() => (int)$quantity
            ]
        );
    }

    /**
     * @When I deliver :quantity items of this product
     */
    public function iDeliverItemsOfThisProduct($quantity)
    {
        $salesOrder = $this->serviceContainer->placeSalesOrderService()->place([
            (string)$this->product->productId() => (int)$quantity
        ]);
        $this->serviceContainer->deliverGoods()->deliver(
            (string)$salesOrder->salesOrderId(),
            [
                (string)$this->product->productId() => (int)$quantity
            ]
        );
    }
}
