<?php

namespace App\Http\Requests\Api\V1\Production;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'production_plan_id' => ['required', 'exists:production_plans,id'],
            'completed_quantity' => ['required', 'numeric', 'gt:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
