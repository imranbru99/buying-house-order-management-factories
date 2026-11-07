<?php

namespace App\Http\Requests\Api\V1\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'internal_number' => ['nullable', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'size:3'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'incoterms' => ['nullable', 'string', 'max:50'],
            'order_date' => ['nullable', 'date'],
            'ex_factory_date' => ['nullable', 'date'],
            'delivery_date' => ['nullable', 'date'],
        ];
    }
}
