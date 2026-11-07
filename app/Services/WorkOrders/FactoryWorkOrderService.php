<?php

namespace App\Services\WorkOrders;

use App\Models\FactoryWorkOrder;
use App\Services\Audit\AuditLogger;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FactoryWorkOrderService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function acknowledge(FactoryWorkOrder $workOrder): FactoryWorkOrder
    {
        return DB::transaction(function () use ($workOrder) {
            if ($workOrder->acknowledged_at !== null) {
                throw new InvalidArgumentException('Work order already acknowledged.');
            }

            $old = [
                'status' => $workOrder->status,
                'acknowledged_at' => $workOrder->acknowledged_at,
            ];

            $workOrder->forceFill([
                'acknowledged_at' => now(),
                'status' => 'acknowledged',
            ])->save();

            $this->auditLogger->log('work_order.acknowledged', $workOrder, $old, [
                'status' => $workOrder->status,
                'acknowledged_at' => $workOrder->acknowledged_at?->toIso8601String(),
            ]);

            return $workOrder->fresh();
        });
    }

    public function accept(FactoryWorkOrder $workOrder): FactoryWorkOrder
    {
        return DB::transaction(function () use ($workOrder) {
            if ($workOrder->accepted_at !== null) {
                throw new InvalidArgumentException('Work order already accepted.');
            }

            if ($workOrder->rejected_at !== null) {
                throw new InvalidArgumentException('Work order already rejected.');
            }

            $old = [
                'status' => $workOrder->status,
                'accepted_at' => $workOrder->accepted_at,
                'rejected_at' => $workOrder->rejected_at,
            ];

            $workOrder->forceFill([
                'accepted_at' => now(),
                'rejected_at' => null,
                'status' => 'accepted',
            ])->save();

            $this->auditLogger->log('work_order.accepted', $workOrder, $old, [
                'status' => $workOrder->status,
                'accepted_at' => $workOrder->accepted_at?->toIso8601String(),
            ]);

            return $workOrder->fresh();
        });
    }

    public function reject(FactoryWorkOrder $workOrder, string $comments): FactoryWorkOrder
    {
        return DB::transaction(function () use ($workOrder, $comments) {
            if ($workOrder->rejected_at !== null) {
                throw new InvalidArgumentException('Work order already rejected.');
            }

            if ($workOrder->accepted_at !== null) {
                throw new InvalidArgumentException('Work order already accepted.');
            }

            $old = [
                'status' => $workOrder->status,
                'accepted_at' => $workOrder->accepted_at,
                'rejected_at' => $workOrder->rejected_at,
                'factory_comments' => $workOrder->factory_comments,
            ];

            $workOrder->forceFill([
                'rejected_at' => now(),
                'accepted_at' => null,
                'status' => 'rejected',
                'factory_comments' => $comments,
            ])->save();

            $this->auditLogger->log('work_order.rejected', $workOrder, $old, [
                'status' => $workOrder->status,
                'rejected_at' => $workOrder->rejected_at?->toIso8601String(),
                'factory_comments' => $workOrder->factory_comments,
            ]);

            return $workOrder->fresh();
        });
    }
}
