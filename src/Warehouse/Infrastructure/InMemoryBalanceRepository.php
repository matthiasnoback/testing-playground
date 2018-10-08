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

namespace Warehouse\Infrastructure;
use Warehouse\Application\Balance;
use Warehouse\Application\BalanceRepository;
use Warehouse\Domain\Model\Product\ProductId;

/**
 * @author Julian Prud'homme <julian.prudhomme@akeneo.com>
 */
class InMemoryBalanceRepository implements BalanceRepository
{
    private $balances;

    public function __construct()
    {
        $this->balances = [];
    }

    public function save(Balance $balance)
    {
        $this->balances[(string) $balance->getProductId()] = $balance;
    }

    public function getByProductId(ProductId $productId)
    {
        if(! array_key_exists((string) $productId, $this->balances))
        {
            throw new \LogicException(sprintf('Product %s not found', (string) $productId));
        }

        return $this->balances[(string) $productId];
    }
}
