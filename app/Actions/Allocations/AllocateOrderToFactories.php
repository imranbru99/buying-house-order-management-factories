<?php

namespace App\Actions\Allocations;

use App\Models\BuyerPurchaseOrder;
use App\Services\Allocation\FactoryAllocationService;

class AllocateOrderToFactories
{
    public function __construct(protected FactoryAllocationService $allocationService) {}

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return list<\App\Models\FactoryAllocation>
     */
    public function execute(BuyerPurchaseOrder $order, array $rows): array
    {
        return $this->allocationService->allocate($order, $rows);
    }
}
