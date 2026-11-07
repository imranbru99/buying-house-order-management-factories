<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Buyer */
class BuyerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'brand_name' => $this->brand_name,
            'country' => $this->country,
            'payment_terms' => $this->payment_terms,
            'credit_limit' => $this->credit_limit,
            'currency' => $this->currency,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
