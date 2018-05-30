<?php
declare(strict_types=1);

namespace Application\Service\PlacePurchaseOrder;

use Application\Service\PlacePurchaseOrder\PlacePurchaseOrder;
use Domain\Model\Product\ProductId;
use Domain\Model\PurchaseOrder\OrderedQuantity;
use Domain\Model\PurchaseOrder\PurchaseOrder;
use Domain\Model\PurchaseOrder\PurchaseOrderRepository;
use Domain\Model\Supplier\SupplierId;

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

    public function place(PlacePurchaseOrder $dto): PurchaseOrder
    {
        $purchaseOrder = PurchaseOrder::create(
            $this->purchaseOrderRepository->nextIdentity(),
            SupplierId::fromString($dto->supplierId)
        );

        foreach ($dto->lines as $line) {
            $purchaseOrder->addLine(
                ProductId::fromString($line->productId),
                new OrderedQuantity($line->quantity)
            );
        }

        $purchaseOrder->place();

        $this->purchaseOrderRepository->save($purchaseOrder);

        return $purchaseOrder;
    }
}
