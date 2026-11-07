<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\BuyerPurchaseOrder */
class BuyerPurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_number' => $this->number,
            'internal_number' => $this->internal_number,
            'buyer_id' => $this->buyer_id,
            'buyer' => BuyerResource::make($this->whenLoaded('buyer')),
            'status' => $this->status,
            'order_date' => $this->order_date,
            'ex_factory_date' => $this->ex_factory_date,
            'delivery_date' => $this->delivery_date,
            'total_quantity' => $this->total_quantity,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,
            'payment_terms' => $this->payment_terms,
            'incoterms' => $this->incoterms,
            'approved_at' => $this->approved_at,
            'confirmed_at' => $this->confirmed_at,
            'items' => $this->whenLoaded('items'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
