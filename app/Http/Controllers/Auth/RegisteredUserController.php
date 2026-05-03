<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $customer = Customer::create([
            'id_shop_group'    => 1,
            'id_shop'          => 1,
            'id_gender'        => $data['id_gender'] ?? 0,
            'id_default_group' => 3, // PS default "Customer" group
            'id_lang'          => (int) config('prestashop.default_lang', 1),
            'id_risk'          => 0,
            'firstname'        => $data['firstname'],
            'lastname'         => $data['lastname'],
            'email'            => $data['email'],
            'passwd'           => Hash::make($data['password']),
            'last_passwd_gen'  => now(),
            'birthday'         => $data['birthday'] ?? null,
            'newsletter'       => !empty($data['newsletter']) ? 1 : 0,
            'optin'            => 0,
            'secure_key'       => md5(Str::uuid()->toString()),
            'active'           => 1,
            'is_guest'         => false,
            'deleted'          => false,
            'date_add'         => now(),
            'date_upd'         => now(),
            'show_public_prices'      => 1,
            'max_payment_days'        => 0,
            'outstanding_allow_amount'=> 0,
        ]);

        $token = $customer->createToken('api')->plainTextToken;

        return response()->json([
            'customer' => new CustomerResource($customer),
            'token'    => $token,
        ], 201);
    }
}
