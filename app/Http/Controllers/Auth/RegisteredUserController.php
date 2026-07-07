<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * If the request comes from a guest session, we convert the existing
     * guest-customer row in-place (update it with real credentials and
     * set is_guest = 0). The id_customer stays the same, so the cart
     * and guest rows need no changes.
     *
     * @throws ValidationException
     */
    public function store(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $guestCustomerId = $request->attributes->get('guest_customer_id');

        // ── Try in-place conversion of existing guest-customer ─────
        if ($guestCustomerId) {
            $guestCustomer = Customer::query()
                ->where('id_customer', $guestCustomerId)
                ->where('is_guest', true)
                ->where('deleted', false)
                ->first();

            if ($guestCustomer) {
                $guestCustomer->update([
                    'firstname'  => $data['firstname'],
                    'lastname'   => $data['lastname'],
                    'email'      => $data['email'],
                    'passwd'     => Hash::make($data['password']),
                    'id_gender'  => $data['id_gender'] ?? 0,
                    'birthday'   => $data['birthday'] ?? null,
                    'newsletter' => !empty($data['newsletter']) ? 1 : 0,
                    'is_guest'   => false,
                    'date_upd'   => now(),
                ]);

                $token = $guestCustomer->createToken('api')->plainTextToken;

                return response()->json([
                    'customer' => new CustomerResource($guestCustomer->refresh()),
                    'token'    => $token,
                ], 201);
            }
        }

        // ── Fallback: normal registration (no guest session) ───────
        $customer = Customer::create([
            'id_shop_group'    => 1,
            'id_shop'          => 1,
            'id_gender'        => $data['id_gender'] ?? 0,
            'id_default_group' => 3, // PS default "Customer" group
            'id_lang'          => (int) config('app.prestashop_lang', 1),
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

