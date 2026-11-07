<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\FactoryWorkOrder */
class FactoryWorkOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'factory_allocation_id' => $this->factory_allocation_id,
            'factory_id' => $this->factory_id,
            'factory' => FactoryResource::make($this->whenLoaded('factory')),
            'buyer_purchase_order_id' => $this->buyer_purchase_order_id,
            'number' => $this->number,
            'status' => $this->status,
            'quantity' => $this->quantity,
            'delivery_date' => $this->delivery_date,
            'acknowledged_at' => $this->acknowledged_at,
            'accepted_at' => $this->accepted_at,
            'rejected_at' => $this->rejected_at,
            'factory_comments' => $this->factory_comments,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
