<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\FactoryAllocation */
class FactoryAllocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'buyer_purchase_order_id' => $this->buyer_purchase_order_id,
            'factory_id' => $this->factory_id,
            'factory' => FactoryResource::make($this->whenLoaded('factory')),
            'style_id' => $this->style_id,
            'allocated_quantity' => $this->allocated_quantity,
            'delivery_date' => $this->delivery_date,
            'status' => $this->status,
            'approved_at' => $this->approved_at,
            'work_orders' => $this->whenLoaded('workOrders'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
