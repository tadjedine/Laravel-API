<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetAddressesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id_address_delivery' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('ps_address', 'id_address'),
            ],
            'id_address_invoice' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('ps_address', 'id_address'),
            ],
        ];
    }

    public function customerId(): int
    {
        return (int) $this->user()->id_customer;
    }
}
