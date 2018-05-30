<?php
declare(strict_types=1);

namespace Application\ReadModel;

use function Common\CommandLine\line;
use function Common\CommandLine\make_yellow;
use Domain\Model\Product\ProductId;

final class BalanceUpdated
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var StockLevel
     */
    private $stockLevel;

    public function __construct(ProductId $productId, StockLevel $stockLevel)
    {
        $this->productId = $productId;
        $this->stockLevel = $stockLevel;
    }

    public function __toString()
    {
        return line(
            make_yellow('Balance updated'),
            sprintf(': product %s, new stock level: %s', $this->productId, $this->stockLevel->asInt())
        );
    }
}
