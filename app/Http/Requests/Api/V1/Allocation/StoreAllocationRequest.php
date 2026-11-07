<?php

namespace App\Http\Requests\Api\V1\Allocation;

use Illuminate\Foundation\Http\FormRequest;

class StoreAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'allocations' => ['required', 'array', 'min:1'],
            'allocations.*.factory_id' => ['required', 'exists:factories,id'],
            'allocations.*.style_id' => ['nullable', 'exists:styles,id'],
            'allocations.*.allocated_quantity' => ['required', 'numeric', 'gt:0'],
            'allocations.*.delivery_date' => ['nullable', 'date'],
        ];
    }
}
