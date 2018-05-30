<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Application\EventSubscriber\UpdatePurchaseOrder;
use Application\Service\CreateReceiptNote\CreateReceiptNote;
use Application\Service\CreateReceiptNote\CreateReceiptNoteService;
use Application\Service\PlacePurchaseOrder\Line as PurchaseOrderLine;
use Application\Service\PlacePurchaseOrder\PlacePurchaseOrder;
use Application\Service\PlacePurchaseOrder\PlacePurchaseOrderService;
use Behat\Behat\Context\Context;
use Common\EventDispatcher\EventDispatcher;
use Domain\Model\PurchaseOrder\PurchaseOrder;
use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\ReceiptNote\GoodsReceived;
use Domain\Model\ReceiptNote\ReceiptNote;
use Domain\Model\ReceiptNote\ReceiptNoteRepository;
use Ramsey\Uuid\Uuid;
use Application\Service\CreateReceiptNote\Line as ReceiptNoteLine;

final class FeatureContext implements Context
{
    /**
     * @var string[]|array
     */
    private $productIds = [];

    /**
     * @var string
     */
    private $supplierId;

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
     * @var PlacePurchaseOrderService
     */
    private $placePurchaseOrderService;

    /**
     * @var CreateReceiptNoteService
     */
    private $createReceiptNoteService;

    /**
     * @BeforeScenario
     */
    public function setUp(): void
    {
        $this->supplierId = Uuid::uuid4()->toString();

        $this->eventDispatcher = new EventDispatcher();
        $this->purchaseOrderRepository = new PurchaseOrderRepository($this->eventDispatcher);
        $updatePurchaseOrderSubscriber = new UpdatePurchaseOrder($this->purchaseOrderRepository);
        $this->eventDispatcher->registerSubscriber(
            GoodsReceived::class,
            [$updatePurchaseOrderSubscriber, 'whenGoodsReceived']
        );
        $this->receiptNoteRepository = new ReceiptNoteRepository($this->eventDispatcher);
        $this->placePurchaseOrderService = new PlacePurchaseOrderService($this->purchaseOrderRepository);
        $this->createReceiptNoteService = new CreateReceiptNoteService($this->purchaseOrderRepository, $this->receiptNoteRepository);
    }

    /**
     * @Given /^the catalog contains product "([^"]*)"$/
     * @param string $name
     */
    public function theCatalogContainsProduct(string $name): void
    {
        $this->productIds[$name] = Uuid::uuid4()->toString();
    }

    /**
     * @Given /^I placed a purchase order with product "([^"]*)", quantity ([\d\.]+)$/
     * @param string $productName
     * @param string $orderedQuantity
     */
    public function iPlacedAPurchaseOrderForProductQuantity(string $productName, string $orderedQuantity): void
    {
        $dto = new PlacePurchaseOrder;
        $dto->supplierId = $this->supplierId;
        $lineDto = new PurchaseOrderLine();
        $lineDto->quantity = (float)$orderedQuantity;
        $lineDto->productId = $this->productIds[$productName];
        $dto->lines[] = $lineDto;

        $this->purchaseOrder = $this->placePurchaseOrderService->place($dto);
    }

    /**
     * @When /^I create[d]? a receipt note for this purchase order, receiving ([\d\.]+) items of product "([^"]*)"$/
     * @param string $receiptQuantity
     * @param string $productName
     */
    public function iCreateAReceiptNoteForThisPurchaseOrderReceivingItemsOfProduct(string $receiptQuantity, string $productName): void
    {
        $dto = new CreateReceiptNote();
        $dto->purchaseOrderId = $this->purchaseOrder->purchaseOrderId()->asString();
        $lineDto = new ReceiptNoteLine();
        $lineDto->productId = $this->productIds[$productName];
        $lineDto->quantity = (float)$receiptQuantity;
        $dto->lines[] = $lineDto;

        $this->receiptNote = $this->createReceiptNoteService->create($dto);
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
