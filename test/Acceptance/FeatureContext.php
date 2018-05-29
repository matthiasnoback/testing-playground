<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Application\UpdatePurchaseOrder;
use Behat\Behat\Context\Context;
use Common\EventDispatcher\EventDispatcher;
use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\OrderedQuantity;
use Domain\Model\PurchaseOrder\PurchaseOrder;
use Domain\Model\PurchaseOrder\PurchaseOrderId;
use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\ReceiptNote\GoodsReceived;
use Domain\Model\ReceiptNote\ReceiptNote;
use Domain\Model\ReceiptNote\ReceiptNoteId;
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
    public function setUp()
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
     */
    public function theCatalogContainsProduct($name)
    {
        $this->products[$name] = ProductId::fromString(Uuid::uuid4()->toString());
    }

    /**
     * @Given /^I placed a purchase order with product "([^"]*)", quantity ([\d\.]+)$/
     */
    public function iPlacedAPurchaseOrderForProductQuantity($productName, $orderedQuantity)
    {
        $this->purchaseOrder = PurchaseOrder::create(
            PurchaseOrderId::fromString(Uuid::uuid4()->toString()),
            $this->supplier);

        $this->purchaseOrder->addLine($this->products[$productName], new OrderedQuantity((float)$orderedQuantity));

        $this->purchaseOrder->place();

        $this->purchaseOrderRepository->save($this->purchaseOrder);
    }

    /**
     * @When /^I create a receipt note for this purchase order, receiving ([\d\.]+) items of product "([^"]*)"$/
     */
    public function iCreateAReceiptNoteForThisPurchaseOrderReceivingItemsOfProduct($arg1, $arg2)
    {
        $this->receiptNote = ReceiptNote::create(
            ReceiptNoteId::fromString(Uuid::uuid4()->toString()),
            $this->purchaseOrder->purchaseOrderId()
        );
        $this->receiptNote->receive($this->products[$arg2], new ReceiptQuantity((float)$arg1));

        $this->receiptNoteRepository->save($this->receiptNote);
    }

    /**
     * @Then /^I expect the purchase order not to be fully delivered yet$/
     */
    public function iExpectThePurchaseOrderNotToBeFullyDeliveredYet()
    {
        assertFalse($this->purchaseOrder->isFullyDelivered());
    }

    /**
     * @Then /^I expect the purchase order to be fully delivered yet$/
     */
    public function iExpectThePurchaseOrderToBeFullyDeliveredYet()
    {
        assertTrue($this->purchaseOrder->isFullyDelivered());
    }
}
