<?php

declare(strict_types=1);

/*
 * This file is part of the Akeneo PIM Enterprise Edition.
 *
 * (c) 2018 Akeneo SAS (http://www.akeneo.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Warehouse\Domain\Model\DeliveryNote;
use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\SalesOrder\SalesOrderId;

/**
 * @author Julian Prud'homme <julian.prudhomme@akeneo.com>
 */
class GoodsDelivered
{
    /** @var ProductId */
    private $productId;
    private $quantity;

    /** @var SalesOrderId */
    private $salesOrderId;

    public function __construct(ProductId $productId,SalesOrderId $salesOrderId, $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->salesOrderId = $salesOrderId;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getSalesOrderId(): SalesOrderId
    {
        return $this->salesOrderId;
    }
}
