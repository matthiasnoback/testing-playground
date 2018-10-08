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
interface BalanceRepository
{
    public function save(Balance $balance);

    public function getByProductId(ProductId $productId);
}
