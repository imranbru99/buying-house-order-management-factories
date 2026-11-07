<?php

namespace App\Actions\Orders;

use App\Models\BuyerPurchaseOrder;
use App\Services\Orders\PurchaseOrderService;

class ApprovePurchaseOrder
{
    public function __construct(protected PurchaseOrderService $purchaseOrderService) {}

    public function execute(BuyerPurchaseOrder $order): BuyerPurchaseOrder
    {
        return $this->purchaseOrderService->approve($order);
    }
}
