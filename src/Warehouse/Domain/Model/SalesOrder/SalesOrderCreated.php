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

namespace Warehouse\Domain\Model\SalesOrder;

/**
 * @author Julian Prud'homme <julian.prudhomme@akeneo.com>
 */
class SalesOrderCreated
{
    /** @var SalesOrderId */
    private $salesOrderId;

    public function __construct(SalesOrderId $salesOrderId)
    {
        $this->salesOrderId = $salesOrderId;
    }
}
