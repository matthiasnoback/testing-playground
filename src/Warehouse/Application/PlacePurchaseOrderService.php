<?php
declare(strict_types=1);

namespace Warehouse\Application;

use Warehouse\Domain\Model\Product\ProductId;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrder;
use Warehouse\Domain\Model\PurchaseOrder\PurchaseOrderRepository;

final class PlacePurchaseOrderService
{
    /**
     * @var PurchaseOrderRepository
     */
    private $purchaseOrderRepository;

    public function __construct(PurchaseOrderRepository $purchaseOrderRepository)
    {
        $this->purchaseOrderRepository = $purchaseOrderRepository;
    }

    public function place(array $productsAndQuantities): PurchaseOrder
    {
        $purchaseOrder = PurchaseOrder::create(
            $this->purchaseOrderRepository->nextIdentity()
        );

        foreach ($productsAndQuantities as $productId => $quantity) {
            $purchaseOrder->addLine(ProductId::fromString($productId), $quantity);
        }

        $purchaseOrder->place();

        $this->purchaseOrderRepository->save($purchaseOrder);

        return $purchaseOrder;
    }
}
