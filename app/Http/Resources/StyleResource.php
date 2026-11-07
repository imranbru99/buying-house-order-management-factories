<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Style */
class StyleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_category_id' => $this->product_category_id,
            'style_number' => $this->style_number,
            'buyer_id' => $this->buyer_id,
            'buyer' => BuyerResource::make($this->whenLoaded('buyer')),
            'code' => $this->code,
            'buyer_style_ref' => $this->buyer_style_ref,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'season' => $this->season,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
