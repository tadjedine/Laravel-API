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
        if ($this->isMethod('delete')) {
            $this->merge(['quantity' => 0]);
        }
        
        $merge = [];
        if ($this->has('id_product_attribute')) {
            $merge['id_product_attribute'] = (int) $this->input('id_product_attribute');
        }
        if ($this->has('id_customization')) {
            $merge['id_customization'] = (int) $this->input('id_customization');
        }
        if ($this->has('id_address_delivery')) {
            $merge['id_address_delivery'] = (int) $this->input('id_address_delivery');
        }
        
        if (!empty($merge)) {
            $this->merge($merge);
        }
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

    public function customerId(): int
    {
        if ($this->user()) {
            return (int) $this->user()->id_customer;
        }

        $guestCustomerId = $this->attributes->get('guest_customer_id');
        if ($guestCustomerId) {
            return (int) $guestCustomerId;
        }

        throw new \RuntimeException('No authenticated user or guest session found.');
    }

    public function quantity()
    {
        return $this->validated('quantity');
    }
    
    public function context(): array
    {
        return array_filter([
            'id_product_attribute' => $this->validated('id_product_attribute'),
            'id_customization' => $this->validated('id_customization'),
            'id_address_delivery' => $this->validated('id_address_delivery'),
            'id_guest'=> $this->attributes->get('guest_id')
            
        ], static fn ($value) => $value !== null);
    }
}
