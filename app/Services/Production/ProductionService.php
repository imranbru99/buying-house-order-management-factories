<?php

namespace App\Services\Production;

use App\Models\FactoryWorkOrder;
use App\Models\ProductionPlan;
use App\Models\ProductionUpdate;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductionService
{
    public function recordUpdate(ProductionPlan $plan, string $completedQuantity, ?string $notes = null): ProductionUpdate
    {
        return DB::transaction(function () use ($plan, $completedQuantity, $notes) {
            $plan->load('workOrder');
            $workOrder = $plan->workOrder;

            if ($workOrder === null) {
                throw new InvalidArgumentException('Production plan is not linked to a work order.');
            }

            $target = (string) $workOrder->quantity;
            $already = (string) ProductionUpdate::query()
                ->where('production_plan_id', $plan->id)
                ->sum('completed_quantity');

            $remaining = bcsub($target, $already, 2);
            if (bccomp($completedQuantity, $remaining, 2) === 1) {
                throw new InvalidArgumentException('Completed quantity exceeds remaining work order quantity.');
            }

            return ProductionUpdate::query()->create([
                'tenant_id' => $plan->tenant_id,
                'production_plan_id' => $plan->id,
                'completed_quantity' => $completedQuantity,
                'reported_at' => now(),
                'notes' => $notes,
            ]);
        });
    }

    public function remainingQuantity(FactoryWorkOrder $workOrder): string
    {
        $planIds = ProductionPlan::query()
            ->where('factory_work_order_id', $workOrder->id)
            ->pluck('id');

        $completed = (string) ProductionUpdate::query()
            ->whereIn('production_plan_id', $planIds)
            ->sum('completed_quantity');

        return bcsub((string) $workOrder->quantity, $completed, 2);
    }
}
