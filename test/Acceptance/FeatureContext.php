<?php
declare(strict_types=1);

namespace Test\Acceptance;

use function assertEquals;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use LogicException;
use RuntimeException;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;
use Warehouse\Infrastructure\ServiceContainer;

final class FeatureContext implements Context
{
    /**
     * @var ServiceContainer
     */
    private $container;

    /**
     * @var ProductId[]
     */
    private $productIds = [];

    /**
     * @var PurchaseOrderId
     */
    private $purchaseOrderId;

    /**
     * @var SalesOrderId
     */
    private $salesOrderId;

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        $this->container = new ServiceContainer();
    }

    /**
     * @Given the catalog contains product :productDescription
     */
    public function theCatalogContainsProduct(string $productDescription)
    {
        $product = $this->container->createProductService()->create($productDescription);
        $this->productIds[$productDescription] = $product->productId();
    }

    /**
     * @Given I placed a purchase order with product :productDescription, quantity :quantity
     */
    public function iPlacedAPurchaseOrderWithProductQuantity(string $productDescription, string $quantity)
    {
        $productId = $this->productIds[$productDescription];
        $purchaseOrder = $this->container->placePurchaseOrderService()->place(
            [
                (string)$productId => (int)$quantity
            ]
        );
        $this->purchaseOrderId = $purchaseOrder->purchaseOrderId();
    }

    /**
     * @When I create a receipt note for this purchase order, receiving :quantity items of product :productDescription
     */
    public function iCreateAReceiptNoteForThisPurchaseOrderReceivingItemsOfProduct($quantity, $productDescription)
    {
        $productId = $this->productIds[$productDescription];
        $this->container->receiveGoodsService()->receive(
            (string)$this->purchaseOrderId,
            [
                (string)$productId => (int)$quantity
            ]
        );
    }

    /**
     * @Then the stock level for product :productDescription will be :quantityInStock
     */
    public function theStockLevelForProductWillBe($productDescription, $quantityInStock)
    {
        $productId = $this->productIds[$productDescription];
        $balance = $this->container->balanceRepository()->getByProductId($productId);

        assertEquals((int)$quantityInStock, $balance->getQuantityInStock());
    }

    /**
     * @Given I have previously received product :productDescription, quantity :quantity
     */
    public function iHavePreviouslyReceivedProductQuantity(string $productDescription, string $quantity)
    {
        $productId = $this->productIds[$productDescription];
        $purchaseOrder = $this->container->placePurchaseOrderService()->place(
            [
                (string)$productId => (int)$quantity
            ]
        );
        $this->container->receiveGoodsService()->receive(
            (string)$purchaseOrder->purchaseOrderId(),
            [
                (string)$productId => (int)$quantity
            ]
        );
    }

    /**
     * @Given I placed a sales order with product :productDescription, quantity :quantity
     */
    public function iPlacedASalesOrderWithProductQuantity($productDescription, $quantity)
    {
        $productId = $this->productIds[$productDescription];

        $salesOrder = $this->container->placeSalesOrderService()->place(
            [
                (string)$productId => (int)$quantity
            ]
        );
        $this->salesOrderId = $salesOrder->salesOrderId();
    }

    /**
     * @When I create a delivery note for this sales order, delivering :quantity items of product :productDescription
     */
    public function iCreateADeliveryNoteForThisSalesOrderDeliveringItemsOfProduct(string $quantity, string $productDescription)
    {
        $productId = $this->productIds[$productDescription];

        $this->container->deliverGoodsService()->deliver(
            (string)$this->salesOrderId,
            [
                (string)$productId => (int)$quantity
            ]
        );
    }

    /**
     * @Then I can't create a delivery note for this sales order, delivering :quantity items of product :productDescription because :exceptionMessage
     */
    public function iCantCreateADeliveryNoteForThisSalesOrderDeliveringItemsOfProduct(string $quantity, string $productDescription, string $exceptionMessage)
    {
        try {
            $productId = $this->productIds[$productDescription];

            $this->container->deliverGoodsService()->deliver(
                (string)$this->salesOrderId,
                [
                    (string)$productId => (int)$quantity
                ]
            );

            throw new LogicException('Expected an exception');
        } catch (RuntimeException $exception) {
            assertEquals($exceptionMessage, $exception->getMessage());
        }
    }
}
