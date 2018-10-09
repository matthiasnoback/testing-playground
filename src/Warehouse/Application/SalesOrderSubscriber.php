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

use Warehouse\Domain\Model\Product\ProductCreated;

/**
 */
class SalesOrderSubscriber
{
    /** @var BalanceRepository */
    private $balanceRepository;

    public function __construct(BalanceRepository $balanceRepository)
    {
        $this->balanceRepository = $balanceRepository;
    }

    public function onProductCreated(ProductCreated $productCreated)
    {
        $balance = new Balance($productCreated->getProductId(), $productCreated->getDescription());

        $this->balanceRepository->save($balance);
    }
}
