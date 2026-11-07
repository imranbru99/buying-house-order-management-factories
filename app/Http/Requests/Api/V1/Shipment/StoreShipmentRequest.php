<?php

namespace App\Http\Requests\Api\V1\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'buyer_purchase_order_id' => ['required', 'exists:buyer_purchase_orders,id'],
            'number' => ['required', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:50'],
            'incoterm' => ['nullable', 'string', 'max:50'],
            'shipping_method' => ['nullable', 'string', 'max:100'],
            'etd' => ['nullable', 'date'],
            'eta' => ['nullable', 'date'],
            'bol_number' => ['nullable', 'string', 'max:100'],
            'allow_overship' => ['nullable', 'boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.buyer_purchase_order_item_id' => ['required', 'exists:buyer_purchase_order_items,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
