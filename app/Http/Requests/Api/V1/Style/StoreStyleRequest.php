<?php

namespace App\Http\Requests\Api\V1\Style;

use Illuminate\Foundation\Http\FormRequest;

class StoreStyleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'style_number' => ['nullable', 'string', 'max:100'],
            'buyer_id' => ['nullable', 'exists:buyers,id'],
            'code' => ['required', 'string', 'max:100'],
            'buyer_style_ref' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'season' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
