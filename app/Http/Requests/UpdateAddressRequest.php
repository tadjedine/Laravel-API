<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAddressRequest extends FormRequest
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
                'sometimes',
                'integer',
                'min:1',
                Rule::exists('ps_country', 'id_country'),
            ],
            'id_state' => [
                'sometimes',
                'nullable',
                'integer',
                'min:1',
                Rule::exists('ps_state', 'id_state'),
            ],

            'alias'        => ['sometimes', 'string', 'max:32'],
            'firstname'    => ['sometimes', 'string', 'max:255'],
            'lastname'     => ['sometimes', 'string', 'max:255'],
            'address1'     => ['sometimes', 'string', 'max:255'],
            'address2'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'postcode'     => ['sometimes', 'nullable', 'string', 'max:12'],
            'city'         => ['sometimes', 'string', 'max:64'],
            'phone'        => ['sometimes', 'nullable', 'string', 'max:32'],
            'phone_mobile' => ['sometimes', 'nullable', 'string', 'max:32'],
            'company'      => ['sometimes', 'nullable', 'string', 'max:255'],
            'vat_number'   => ['sometimes', 'nullable', 'string', 'max:32'],
            'dni'          => ['sometimes', 'nullable', 'string', 'max:16'],
            'other'        => ['sometimes', 'nullable', 'string', 'max:300'],
        ];
    }

    /**
     * Get the authenticated customer's ID.
     */
    public function customerId(): int
    {
        return (int) $this->user()->id_customer;
    }
}
