<?php

namespace App\Services\Allocation;

use App\Events\AllocationCreated;
use App\Models\BuyerPurchaseOrder;
use App\Models\FactoryAllocation;
use App\Models\FactoryWorkOrder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FactoryAllocationService
{
    public function allocate(BuyerPurchaseOrder $order, array $rows): array
    {
        return DB::transaction(function () use ($order, $rows) {
            $order->load('items');
            $orderTotal = (string) ($order->total_quantity ?: $this->sumOrderItemQty($order));
            $existing = (string) FactoryAllocation::query()
                ->where('buyer_purchase_order_id', $order->id)
                ->sum('allocated_quantity');

            $incoming = '0.00';
            foreach ($rows as $row) {
                $incoming = bcadd($incoming, (string) ($row['allocated_quantity'] ?? $row['quantity'] ?? 0), 2);
            }

            if (bccomp(bcadd($existing, $incoming, 2), $orderTotal, 2) === 1) {
                throw new InvalidArgumentException('Allocation would exceed PO total quantity.');
            }

            $created = [];
            foreach ($rows as $row) {
                $allocation = FactoryAllocation::query()->create([
                    'tenant_id' => $order->tenant_id,
                    'buyer_purchase_order_id' => $order->id,
                    'factory_id' => $row['factory_id'],
                    'style_id' => $row['style_id'] ?? null,
                    'allocated_quantity' => $row['allocated_quantity'] ?? $row['quantity'],
                    'delivery_date' => $row['delivery_date'] ?? null,
                    'status' => $row['status'] ?? 'planned',
                ]);

                event(new AllocationCreated($allocation));
                $created[] = $allocation;
            }

            return $created;
        });
    }

    public function unallocatedQuantity(BuyerPurchaseOrder $order): string
    {
        $total = (string) ($order->total_quantity ?: $this->sumOrderItemQty($order));
        $allocated = (string) FactoryAllocation::query()
            ->where('buyer_purchase_order_id', $order->id)
            ->sum('allocated_quantity');

        return bcsub($total, $allocated, 2);
    }

    public function approve(FactoryAllocation $allocation): FactoryWorkOrder
    {
        return DB::transaction(function () use ($allocation) {
            if ($allocation->status === 'approved') {
                throw new InvalidArgumentException('Allocation already approved.');
            }

            $allocation->forceFill([
                'status' => 'approved',
                'approved_at' => now(),
            ])->save();

            $number = sprintf('FWO-%s-%s', $allocation->buyer_purchase_order_id, $allocation->id);

            return FactoryWorkOrder::query()->create([
                'tenant_id' => $allocation->tenant_id,
                'factory_allocation_id' => $allocation->id,
                'factory_id' => $allocation->factory_id,
                'buyer_purchase_order_id' => $allocation->buyer_purchase_order_id,
                'number' => $number,
                'quantity' => $allocation->allocated_quantity,
                'delivery_date' => $allocation->delivery_date,
                'status' => 'open',
            ]);
        });
    }

    protected function sumOrderItemQty(BuyerPurchaseOrder $order): string
    {
        $sum = '0.00';
        foreach ($order->items as $item) {
            $sum = bcadd($sum, (string) $item->quantity, 2);
        }

        $order->forceFill(['total_quantity' => $sum])->save();

        return $sum;
    }
}
