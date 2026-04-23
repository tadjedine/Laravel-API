<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

 // Form Request to validate the request for deleting or updating a cart item

class CartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id_product_attribute' => (int) ($this->input('id_product_attribute') ?? 0),
            'id_customization' => (int) ($this->input('id_customization')),
            'id_address_delivery' => (int) ($this->input('id_address_delivery')),
        ]);
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
            'quantity' => ['required', 'integer', 'min:0'],

            // product id comes from route parameter {id}
            'id_product_attribute' => [
                'nullable',
                'integer',
                'min:0',
                
                Rule::when((int) $this->input('id_product_attribute') > 0, [
                    Rule::exists('ps_product_attribute', 'id_product_attribute')
                        ->where(fn ($q) => $q->where('id_product', (int) $this->route('productId')))
                ]),
            ],

            'id_customization' => ['nullable', 'integer', 'min:0'],
            'id_address_delivery' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function customerId()
    {
        return $this->validated('id_customer');
    }

    public function quantity()
    {
        return $this->validated('quantity');
    }
    
    public function context(): array
    {
        return [
            'id_product_attribute' => (int) $this->validated('id_product_attribute', 0),
            'id_customization' => (int) $this->validated('id_customization', 0),
            'id_address_delivery' => (int) $this->validated('id_address_delivery'),
        ];
    }
}
