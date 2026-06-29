<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Customer;
use App\Models\Guest;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function __construct(private CartService $cartService) {}

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $customer = $request->authenticate();
        $token = $customer->createToken('api')->plainTextToken;

        // ── Cart Transfer ──────────────────────────────────────────
        $guestCustomerId = $request->attributes->get('guest_customer_id');

        if ($guestCustomerId && $guestCustomerId !== (int) $customer->id_customer) {
            $this->transferGuestCart($guestCustomerId, (int) $customer->id_customer);
        }
        // ── End Cart Transfer ──────────────────────────────────────

        return response()->json([
            "user" => new CustomerResource($customer),
            "token" => $token,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        if ($token = $request->user()?->currentAccessToken()) {
            // PersonalAccessToken vs TransientToken (cookie session) check
            if (method_exists($token, 'delete')) {
                $token->delete();
            }
        }

        return response()->noContent();
    }

    // ── Helpers ──────────────────────────────────────────────────────

    /**
     * Transfer a guest's cart to the real customer account.
     *
     * If the real customer already has an open cart, merge the guest items into it.
     * Otherwise, simply reassign the guest cart to the real customer.
     * Then soft-delete the orphaned guest-customer row.
     */
    private function transferGuestCart(int $guestCustomerId, int $realCustomerId): void
    {
        $guestCart = Cart::query()
            ->where('id_customer', $guestCustomerId)
            ->whereDoesntHave('order')
            ->orderByDesc('id_cart')
            ->first();

        if ($guestCart) {
            $existingCart = Cart::query()
                ->where('id_customer', $realCustomerId)
                ->whereDoesntHave('order')
                ->orderByDesc('id_cart')
                ->first();

            if ($existingCart) {
                // Merge: move guest cart items into the existing customer cart
                $guestCart->load('products');
                foreach ($guestCart->products as $line) {
                    $this->cartService->addProduct(
                        (int) $existingCart->id_cart,
                        (int) $line->id_product,
                        (int) $line->quantity,
                        (int) $line->id_product_attribute,
                        (int) ($line->id_customization ?? 0),
                        (int) $existingCart->id_address_delivery,
                    );
                }

                // Clean up the now-empty guest cart
                CartProduct::query()->where('id_cart', $guestCart->id_cart)->delete();
            } else {
                // Claim: simply reassign the guest cart to the real customer
                $guestCart->id_customer = $realCustomerId;
                $guestCart->date_upd = Carbon::now();
                $guestCart->save();
            }
        }

        // Update ps_guest to point to the real customer
        Guest::query()
            ->where('id_customer', $guestCustomerId)
            ->update(['id_customer' => $realCustomerId]);

        // Soft-delete the orphaned guest-customer row
        Customer::query()
            ->where('id_customer', $guestCustomerId)
            ->where('is_guest', true)
            ->update(['deleted' => true, 'date_upd' => Carbon::now()]);
    }
}
