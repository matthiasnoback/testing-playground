<?php

namespace Warehouse\Domain\Model\Balance;

interface BalanceRepository
{
    public function save(Balance $balance): void;
}
