<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Exception;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;
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
     * @var SalesOrderId
     */
    private $salesOrderId;

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

    /**
     * @When I create a sales order for :quantity items of this product
     */
    public function iCreateASalesOrderForItemsOfThisProduct(int $quantity): void
    {
        $this->salesOrderId = $this->serviceContainer->placeSalesOrderService()
            ->place(
                [
                    (string)$this->productId => $quantity
                ]
            );
    }

    /**
     * @Then I can not deliver the sales order
     */
    public function iCanNotDeliverTheSalesOrder(): void
    {
        $this->expectException(
            function () {
                $this->serviceContainer->deliverGoodsService()
                    ->deliver((string)$this->salesOrderId);
            },
            RuntimeException::class,
            'can not be delivered'
        );
    }

    private function expectException(callable $function, string $exceptionClass, string $exceptionMessage): void
    {
        try {
            $function();

            throw new ExpectedAnException();
        } catch (Exception $exception) {
            if ($exception instanceof ExpectedAnException) {
                throw $exception;
            }

            Assert::assertInstanceOf($exceptionClass, $exception);
            Assert::assertContains(
                $exceptionMessage,
                $exception->getMessage()
            );
        }
    }
}
