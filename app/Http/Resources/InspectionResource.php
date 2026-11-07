<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Inspection */
class InspectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'factory_work_order_id' => $this->factory_work_order_id,
            'number' => $this->number,
            'type' => $this->type,
            'status' => $this->status,
            'defects' => $this->whenLoaded('defects'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
