<?php
declare(strict_types=1);

namespace Domain\Model\Supplier;

final class Supplier
{
    /**
     * @var SupplierId
     */
    private $supplierId;

    /**
     * @var string
     */
    private $name;

    public function __construct(SupplierId $supplierId, string $name)
    {
        $this->supplierId = $supplierId;
        $this->name = $name;
    }

    public function supplierId(): SupplierId
    {
        return $this->supplierId;
    }
}
