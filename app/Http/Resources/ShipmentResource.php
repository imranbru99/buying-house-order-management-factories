<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Shipment */
class ShipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'buyer_purchase_order_id' => $this->buyer_purchase_order_id,
            'purchase_order' => BuyerPurchaseOrderResource::make($this->whenLoaded('purchaseOrder')),
            'number' => $this->number,
            'status' => $this->status,
            'incoterm' => $this->incoterm,
            'shipping_method' => $this->shipping_method,
            'etd' => $this->etd,
            'atd' => $this->atd,
            'eta' => $this->eta,
            'ata' => $this->ata,
            'bol_number' => $this->bol_number,
            'items' => $this->whenLoaded('items'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
