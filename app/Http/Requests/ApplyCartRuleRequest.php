<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCartRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'min:1'],
            'code'        => ['required', 'string', 'max:255'],
            'id_lang'     => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function customerId(): int
    {
        return (int) $this->validated('customer_id');
    }

    public function code(): string
    {
        return (string) $this->validated('code');
    }

    public function idLang(): int
    {
        return (int) $this->validated('id_lang', 1) ?: 1;
    }
}
