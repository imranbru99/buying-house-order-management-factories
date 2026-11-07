<?php

namespace App\Http\Requests\Api\V1\Buyer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuyerRequest extends FormRequest
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
            'brand_name' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'size:2'],
            'payment_terms' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['nullable', 'numeric'],
            'currency' => ['nullable', 'string', 'size:3'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
