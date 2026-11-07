<?php

namespace App\Http\Requests\Api\V1\Inspection;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'factory_work_order_id' => ['nullable', 'exists:factory_work_orders,id'],
            'number' => ['required', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:50'],
            'critical_limit' => ['nullable', 'integer', 'min:0'],
            'defects' => ['nullable', 'array'],
            'defects.*.defect_code' => ['required_with:defects', 'string', 'max:100'],
            'defects.*.quantity' => ['required_with:defects', 'numeric', 'gt:0'],
            'defects.*.severity' => ['nullable', 'string', 'max:50'],
        ];
    }
}
