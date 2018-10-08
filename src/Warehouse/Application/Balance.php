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

namespace Warehouse\Application;
use Warehouse\Domain\Model\Product\ProductId;

/**
 * @author Julian Prud'homme <julian.prudhomme@akeneo.com>
 */
class Balance
{
    private $productId;

    private $stock;

    private $description;

    public function __construct(ProductId $productId, string $description)
    {
        $this->productId = $productId;
        $this->description = $description;
        $this->stock = 0;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function increase($quantity)
    {
        $balance = clone $this;
        $balance->stock += $quantity;

        return $balance;
    }

    public function decrease($quantity)
    {
        $balance = clone $this;
        $balance->stock -= $quantity;

        return $balance;
    }
}
