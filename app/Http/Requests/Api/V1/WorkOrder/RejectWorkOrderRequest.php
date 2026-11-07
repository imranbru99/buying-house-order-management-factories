<?php

namespace App\Http\Requests\Api\V1\WorkOrder;

use Illuminate\Foundation\Http\FormRequest;

class RejectWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'factory_comments' => ['required', 'string'],
        ];
    }
}
