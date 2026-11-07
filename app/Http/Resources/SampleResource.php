<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Sample */
class SampleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'style_id' => $this->style_id,
            'style' => StyleResource::make($this->whenLoaded('style')),
            'buyer_id' => $this->buyer_id,
            'buyer' => BuyerResource::make($this->whenLoaded('buyer')),
            'factory_id' => $this->factory_id,
            'factory' => FactoryResource::make($this->whenLoaded('factory')),
            'number' => $this->number,
            'sample_type' => $this->sample_type,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
