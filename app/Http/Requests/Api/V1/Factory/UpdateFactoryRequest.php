<?php

namespace App\Http\Requests\Api\V1\Factory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFactoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'code' => ['sometimes', 'string', 'max:50'],
            'name' => ['sometimes', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'size:2'],
            'monthly_capacity' => ['nullable', 'numeric'],
            'available_capacity' => ['nullable', 'numeric'],
            'approval_status' => ['nullable', 'string', 'max:50'],
            'quality_score' => ['nullable', 'numeric'],
            'delivery_score' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
