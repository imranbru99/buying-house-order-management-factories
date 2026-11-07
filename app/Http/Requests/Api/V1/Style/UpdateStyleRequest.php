<?php

namespace App\Http\Requests\Api\V1\Style;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStyleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'product_category_id' => ['sometimes', 'nullable', 'exists:product_categories,id'],
            'style_number' => ['sometimes', 'nullable', 'string', 'max:100'],
            'buyer_id' => ['sometimes', 'nullable', 'exists:buyers,id'],
            'code' => ['sometimes', 'string', 'max:100'],
            'buyer_style_ref' => ['sometimes', 'nullable', 'string', 'max:100'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'season' => ['sometimes', 'nullable', 'string', 'max:100'],
            'status' => ['sometimes', 'nullable', 'string', 'max:50'],
        ];
    }
}
