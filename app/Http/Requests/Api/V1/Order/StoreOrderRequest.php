<?php

namespace App\Http\Requests\Api\V1\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'buyer_id' => ['required', 'exists:buyers,id'],
            'number' => ['required', 'string', 'max:100'],
            'internal_number' => ['nullable', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'size:3'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'incoterms' => ['nullable', 'string', 'max:50'],
            'order_date' => ['nullable', 'date'],
            'ex_factory_date' => ['nullable', 'date'],
            'delivery_date' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.style_id' => ['nullable', 'exists:styles,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['required', 'numeric', 'gte:0'],
            'items.*.line_no' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
