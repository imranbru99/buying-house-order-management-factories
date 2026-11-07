<?php

namespace App\Services\Orders;

use App\Events\OrderApproved;
use App\Models\BuyerPurchaseOrder;
use App\Models\BuyerPurchaseOrderItem;
use App\Services\Audit\AuditLogger;
use App\Services\Costing\CostingCalculator;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PurchaseOrderService
{
    public function __construct(
        protected AuditLogger $auditLogger,
        protected CostingCalculator $calculator,
    ) {}

    /**
     * @param  array<string, mixed>  $header
     * @param  list<array<string, mixed>>  $items
     */
    public function create(array $header, array $items): BuyerPurchaseOrder
    {
        return DB::transaction(function () use ($header, $items) {
            $poNumber = $header['number'] ?? $header['po_number'] ?? null;
            if ($poNumber === null) {
                throw new InvalidArgumentException('PO number is required.');
            }

            $order = BuyerPurchaseOrder::query()->create([
                ...$header,
                'number' => $poNumber,
                'status' => $header['status'] ?? 'draft',
            ]);

            $totalQty = '0.00';
            $totalAmount = '0.0000';

            foreach ($items as $index => $row) {
                $qty = (string) ($row['quantity'] ?? 0);
                $unitPrice = (string) ($row['unit_price'] ?? 0);
                $lineTotal = $this->calculator->multiply($qty, $unitPrice);

                BuyerPurchaseOrderItem::query()->create([
                    'tenant_id' => $order->tenant_id,
                    'buyer_purchase_order_id' => $order->id,
                    'style_id' => $row['style_id'] ?? null,
                    'line_no' => $row['line_no'] ?? ($index + 1),
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);

                $totalQty = bcadd($totalQty, $qty, 2);
                $totalAmount = bcadd($totalAmount, $lineTotal, 4);
            }

            $order->forceFill([
                'total_quantity' => $totalQty,
                'total_amount' => $totalAmount,
            ])->save();

            $this->auditLogger->log('purchase_order.created', $order, null, $order->toArray());

            return $order->fresh('items');
        });
    }

    public function approve(BuyerPurchaseOrder $order): BuyerPurchaseOrder
    {
        return $this->transition($order, 'draft', 'approved', 'purchase_order.approved', function (BuyerPurchaseOrder $po) {
            $po->forceFill(['approved_at' => now()])->save();
            event(new OrderApproved($po));
        });
    }

    public function confirm(BuyerPurchaseOrder $order): BuyerPurchaseOrder
    {
        return $this->transition($order, 'approved', 'confirmed', 'purchase_order.confirmed', function (BuyerPurchaseOrder $po) {
            $po->forceFill(['confirmed_at' => now()])->save();
        });
    }

    public function cancel(BuyerPurchaseOrder $order): BuyerPurchaseOrder
    {
        return $this->transition($order, ['draft', 'approved', 'confirmed'], 'cancelled', 'purchase_order.cancelled');
    }

    /**
     * @param  string|list<string>  $from
     */
    protected function transition(
        BuyerPurchaseOrder $order,
        string|array $from,
        string $to,
        string $auditEvent,
        ?callable $after = null,
    ): BuyerPurchaseOrder {
        $allowed = is_array($from) ? $from : [$from];

        if (! in_array($order->status, $allowed, true)) {
            throw new InvalidArgumentException("Cannot move PO from [{$order->status}] to [{$to}].");
        }

        return DB::transaction(function () use ($order, $to, $auditEvent, $after) {
            $old = $order->only(['status', 'approved_at', 'confirmed_at']);
            $order->forceFill(['status' => $to])->save();

            if ($after) {
                $after($order);
            }

            $this->auditLogger->log($auditEvent, $order, $old, $order->fresh()->only(['status', 'approved_at', 'confirmed_at']));

            return $order->fresh('items');
        });
    }
}
