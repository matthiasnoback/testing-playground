<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
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
        throw new PendingException();
    }
}
