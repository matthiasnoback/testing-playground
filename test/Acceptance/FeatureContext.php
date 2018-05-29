<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Application\UpdatePurchaseOrder;
use Behat\Behat\Context\Context;
use Common\EventDispatcher\EventDispatcher;
use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\OrderedQuantity;
use Domain\Model\PurchaseOrder\PurchaseOrder;
use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\ReceiptNote\GoodsReceived;
use Domain\Model\ReceiptNote\ReceiptNote;
use Domain\Model\ReceiptNote\ReceiptNoteRepository;
use Domain\Model\ReceiptNote\ReceiptQuantity;
use Domain\Model\Supplier\SupplierId;
use Ramsey\Uuid\Uuid;

final class FeatureContext implements Context
{
    /**
     * @var ProductId[]|array
     */
    private $products = [];

    /**
     * @var SupplierId
     */
    private $supplier;

    /**
     * @var PurchaseOrder
     */
    private $purchaseOrder;

    /**
     * @var ReceiptNote
     */
    private $receiptNote;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var PurchaseOrderRepository
     */
    private $purchaseOrderRepository;

    /**
     * @var ReceiptNoteRepository
     */
    private $receiptNoteRepository;

    /**
     * @BeforeScenario
     */
    public function setUp(): void
    {
        $this->supplier = SupplierId::fromString(Uuid::uuid4()->toString());

        $this->eventDispatcher = new EventDispatcher();
        $this->purchaseOrderRepository = new PurchaseOrderRepository($this->eventDispatcher);
        $updatePurchaseOrderSubscriber = new UpdatePurchaseOrder($this->purchaseOrderRepository);
        $this->eventDispatcher->registerSubscriber(
            GoodsReceived::class,
            [$updatePurchaseOrderSubscriber, 'whenGoodsReceived']
        );
        $this->receiptNoteRepository = new ReceiptNoteRepository($this->eventDispatcher);
    }

    /**
     * @Given /^the catalog contains product "([^"]*)"$/
     * @param string $name
     */
    public function theCatalogContainsProduct(string $name): void
    {
        $this->products[$name] = ProductId::fromString(Uuid::uuid4()->toString());
    }

    /**
     * @Given /^I placed a purchase order with product "([^"]*)", quantity ([\d\.]+)$/
     * @param string $productName
     * @param string $orderedQuantity
     */
    public function iPlacedAPurchaseOrderForProductQuantity(string $productName, string $orderedQuantity): void
    {
        $this->purchaseOrder = PurchaseOrder::create(
            $this->purchaseOrderRepository->nextIdentity(),
            $this->supplier);

        $this->purchaseOrder->addLine($this->products[$productName], new OrderedQuantity((float)$orderedQuantity));

        $this->purchaseOrder->place();

        $this->purchaseOrderRepository->save($this->purchaseOrder);
    }

    /**
     * @When /^I create[d]? a receipt note for this purchase order, receiving ([\d\.]+) items of product "([^"]*)"$/
     * @param string $receiptQuantity
     * @param string $productName
     */
    public function iCreateAReceiptNoteForThisPurchaseOrderReceivingItemsOfProduct(string $receiptQuantity, string $productName): void
    {
        $this->receiptNote = ReceiptNote::create(
            $this->receiptNoteRepository->nextIdentity(),
            $this->purchaseOrder->purchaseOrderId()
        );
        $this->receiptNote->receive($this->products[$productName], new ReceiptQuantity((float)$receiptQuantity));

        $this->receiptNoteRepository->save($this->receiptNote);
    }

    /**
     * @Then /^I expect the purchase order not to be fully delivered yet$/
     */
    public function iExpectThePurchaseOrderNotToBeFullyDeliveredYet(): void
    {
        assertFalse($this->purchaseOrder->isFullyDelivered());
    }

    /**
     * @Then /^I expect the purchase order to be fully delivered/
     */
    public function iExpectThePurchaseOrderToBeFullyDelivered(): void
    {
        assertTrue($this->purchaseOrder->isFullyDelivered());
    }
}
