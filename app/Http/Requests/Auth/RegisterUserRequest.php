<?php

namespace App\Http\Requests\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
{
    public function rules():array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname'  => ['required', 'string', 'max:255'],
            'email'     => [
                'required', 'string', 'email', 'max:255',
                Rule::unique(Customer::class, 'email')->where(fn ($q) => $q->where('deleted', 0)),
            ],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'birthday'  => ['nullable', 'date'],
            'id_gender' => ['nullable', 'integer', 'in:0,1,2'],
            'newsletter'=> ['nullable', 'boolean'],
        ];
    }


    public function authorize():bool
    {
        return true;
    }
}
