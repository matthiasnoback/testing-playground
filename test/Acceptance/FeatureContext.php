<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Assert\Assert;
use function assertEquals;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Warehouse\Domain\Model\Product\Product;
use Warehouse\Domain\Model\SalesOrder\SalesOrder;
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
     * @var SalesOrder
     */
    private $salesOrder;

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
            (string)$salesOrder->salesOrderId()
        );
    }

    /**
     * @When I create a sales order for :orderedQuantity items of this product
     */
    public function iCreateASalesOrderForItemsOfThisProduct($orderedQuantity)
    {
        $this->salesOrder = $this->serviceContainer->placeSalesOrderService()->place([
             (string)$this->product->productId() => (int)$orderedQuantity
        ]);
    }

    /**
     * @Then I can not deliver the sales order
     */
    public function iCanNotDeliverTheSalesOrder()
    {
        try {
            $this->serviceContainer->deliverGoods()->deliver(
                (string) $this->salesOrder->salesOrderId()
            );
            throw new \RuntimeException();
        } catch (\RuntimeException $e) {
            assertContains('Your order can not be delivered', $e->getMessage());
        }
    }

    /**
     * @Then this sales order should be deliverable
     */
    public function thisSalesOrderShouldBeDeliverable()
    {
        assertTrue($this->salesOrder->isDeliverable());
    }
}
