<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAddressRequest extends FormRequest
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
            'id_country' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('ps_country', 'id_country'),
            ],
            'id_state' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('ps_state', 'id_state'),
            ],

            'alias'        => ['required', 'string', 'max:32'],
            'firstname'    => ['required', 'string', 'max:255'],
            'lastname'     => ['required', 'string', 'max:255'],
            'address1'     => ['required', 'string', 'max:255'],
            'address2'     => ['nullable', 'string', 'max:255'],
            'postcode'     => ['nullable', 'string', 'max:12'],
            'city'         => ['required', 'string', 'max:64'],
            'phone'        => ['nullable', 'string', 'max:32'],
            'phone_mobile' => ['nullable', 'string', 'max:32'],
            'company'      => ['nullable', 'string', 'max:255'],
            'vat_number'   => ['nullable', 'string', 'max:32'],
            'dni'          => ['nullable', 'string', 'max:16'],
            'other'        => ['nullable', 'string', 'max:300'],
        ];
    }

    /**
     * Get the authenticated customer's ID.
     * The controller should use this — never trust id_customer from the body.
     */
    public function customerId(): int
    {
        return (int) $this->user()->id_customer;
    }
}

