<?php

namespace App\Http\Requests\Checkout;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuestCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Guest contact info
            'email'          => [
                'required', 'email', 'max:255',
            ],
            'firstname'      => ['required', 'string', 'max:255'],
            'lastname'       => ['required', 'string', 'max:255'],

            // Shipping address
            'address1'       => ['required', 'string', 'max:255'],
            'address2'       => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:255'],
            'postcode'       => ['nullable', 'string', 'max:12'],
            'id_country'     => ['required', 'integer', Rule::exists('ps_country', 'id_country')],
            'phone'          => ['nullable', 'string', 'max:32'],

            // Carrier & Payment
            'id_carrier'     => ['required', 'integer'],
            'payment_method' => ['required', 'string'],
        ];
    }
}
