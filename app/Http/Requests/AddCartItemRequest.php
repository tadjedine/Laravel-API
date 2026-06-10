<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCartItemRequest extends FormRequest
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
            // 'id_customer' => ['required', 'integer', 'min:1'],
            'id_product' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('ps_product', 'id_product'),
            ],
            'quantity' => ['required', 'integer', 'min:1'],

            // variant/combination
            'id_product_attribute' => [
                'nullable',
                'integer',
                'min:0',
                Rule::when(
                    $this->input('id_product_attribute') > 0,
                    Rule::exists('ps_product_attribute', 'id_product_attribute')
                        ->where(fn ($q) => $q->where('id_product', $this->input('id_product')))
                ),
            ],

            // cart context used by service
            'id_customization' => ['nullable', 'integer', 'min:0'],
            'id_address_delivery' => ['nullable', 'integer', 'min:0'],
            'id_shop' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function customerId(): int
    {
        return (int) $this->user()->id_customer;
    }

    public function productId(): int
    {
        return (int) $this->validated('id_product');
    }

    public function quantity(): int
    {
        return (int) $this->validated('quantity');
    }

    public function context(): array
    {
        return array_filter([
            'id_product_attribute' => $this->validated('id_product_attribute'),
            'id_customization' => $this->validated('id_customization'),
            'id_address_delivery' => $this->validated('id_address_delivery'),
            'id_shop' => $this->validated('id_shop'),
        ], static fn ($value) => $value !== null);
    }

}
