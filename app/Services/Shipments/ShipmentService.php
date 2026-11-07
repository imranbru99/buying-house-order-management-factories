<?php

namespace App\Services\Shipments;

use App\Events\ShipmentCreated;
use App\Models\BuyerPurchaseOrder;
use App\Models\BuyerPurchaseOrderItem;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ShipmentService
{
    public function create(BuyerPurchaseOrder $order, array $header, array $items, bool $allowOvership = false): Shipment
    {
        return DB::transaction(function () use ($order, $header, $items, $allowOvership) {
            $shipment = Shipment::query()->create([
                'tenant_id' => $order->tenant_id,
                'buyer_purchase_order_id' => $order->id,
                'number' => $header['number'],
                'status' => $header['status'] ?? 'planned',
                'incoterm' => $header['incoterm'] ?? $order->incoterms,
                'shipping_method' => $header['shipping_method'] ?? null,
                'etd' => $header['etd'] ?? null,
                'eta' => $header['eta'] ?? null,
                'bol_number' => $header['bol_number'] ?? null,
            ]);

            foreach ($items as $row) {
                $poItem = BuyerPurchaseOrderItem::query()->findOrFail($row['buyer_purchase_order_item_id']);
                $qty = (string) $row['quantity'];

                $shipped = (string) ShipmentItem::query()
                    ->where('buyer_purchase_order_item_id', $poItem->id)
                    ->sum('quantity');

                $newTotal = bcadd($shipped, $qty, 2);
                if (! $allowOvership && bccomp($newTotal, (string) $poItem->quantity, 2) === 1) {
                    throw new InvalidArgumentException('Shipment quantity exceeds order line quantity.');
                }

                ShipmentItem::query()->create([
                    'tenant_id' => $order->tenant_id,
                    'shipment_id' => $shipment->id,
                    'buyer_purchase_order_item_id' => $poItem->id,
                    'quantity' => $qty,
                ]);
            }

            event(new ShipmentCreated($shipment));

            return $shipment->fresh('items');
        });
    }
}
