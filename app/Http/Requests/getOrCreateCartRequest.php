<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class getOrCreateCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_customer' => ['required', 'integer', 'min:1'],
            'id_shop_group' => ['nullable', 'integer', 'min:1'],
            'id_shop' => ['nullable', 'integer', 'min:1'],
            'id_lang' => ['nullable', 'integer', 'min:1'],
            'id_currency' => ['nullable', 'integer', 'min:1'],
            'id_address_delivery' => ['nullable', 'integer', 'min:0'],
            'id_address_invoice' => ['nullable', 'integer', 'min:0'],
            'id_guest' => ['nullable', 'integer', 'min:0'],
            'delivery_option' => ['nullable', 'string'],
        ];
    }

    public function customerId(): int
    {
        return (int) $this->validated('id_customer');
    }

    public function context(): array
    {
        return array_filter([
            'id_shop_group' => $this->validated('id_shop_group'),
            'id_shop' => $this->validated('id_shop'),
            'id_lang' => $this->validated('id_lang'),
            'id_currency' => $this->validated('id_currency'),
            'id_address_delivery' => $this->validated('id_address_delivery'),
            'id_address_invoice' => $this->validated('id_address_invoice'),
            'id_guest' => $this->validated('id_guest'),
            'delivery_option' => $this->validated('delivery_option'),
        ], static fn ($value) => $value !== null);
    }
}
