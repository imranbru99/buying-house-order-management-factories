<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Factory */
class FactoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'country' => $this->country,
            'monthly_capacity' => $this->monthly_capacity,
            'available_capacity' => $this->available_capacity,
            'approval_status' => $this->approval_status,
            'quality_score' => $this->quality_score,
            'delivery_score' => $this->delivery_score,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
