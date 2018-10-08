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

namespace Warehouse\Domain\Model\Product;

/**
 * @author Julian Prud'homme <julian.prudhomme@akeneo.com>
 */
class ProductCreated
{
    /** @var ProductId */
    private $productId;
    private $description;

    public function __construct(ProductId $productId, $description)
    {
        $this->productId = $productId;
        $this->description = $description;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


}
