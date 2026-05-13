<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveCartRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'min:1'],
        ];
    }

    public function customerId(): int
    {
        return (int) $this->validated('customer_id');
    }

    public function code(): string
    {
        // code comes from the route parameter {code}
        return (string) $this->route('code');
    }
}
