<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetCarrierRequest extends FormRequest
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
            'id_carrier' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('ps_carrier', 'id_carrier'),
            ],
        ];
    }

    public function customerId(): int
    {
        return (int) $this->user()->id_customer;
    }
}
