<?php

namespace App\Http\Requests\Api\V1\Sample;

use Illuminate\Foundation\Http\FormRequest;

class TransitionSampleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'max:50'],
            'factory_id' => ['nullable', 'exists:factories,id'],
        ];
    }
}
