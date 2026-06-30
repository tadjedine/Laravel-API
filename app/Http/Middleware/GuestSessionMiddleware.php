<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Guest;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class GuestSessionMiddleware
{

    public const COOKIE_NAME = 'guest_session_id';
    public const COOKIE_MINUTES = 43200; // 30 days

    /**
     * Handle an incoming request.
     *
     * This middleware is LAZY — it only reads an existing guest cookie
     * and attaches guest info to the request. It does NOT create new
     * guest/customer rows. That happens on demand via createGuestSession().
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if user is already authenticated via Sanctum
        if ($request->user()) {
            return $next($request);
        }

        $guestId = $request->cookie(self::COOKIE_NAME);

        if ($guestId) {
            $guest = Guest::query()->find((int) $guestId);

            if ($guest) {
                $request->attributes->set('guest', $guest);
                $request->attributes->set('guest_id', (int) $guest->id_guest);
                $request->attributes->set('guest_customer_id', (int) $guest->id_customer);
            }
            // If cookie points to a deleted/nonexistent guest, we just ignore it.
            // The controller will call createGuestSession() when needed.
        }

        return $next($request);
    }

    /**
     * Create a new guest session (guest-customer + guest row) on demand.
     *
     * Called by CartController (or similar) when a guest performs an action
     * that requires a session (e.g., adding to cart) and doesn't have one yet.
     *
     * Returns the Guest model and queues the cookie.
     */
    public static function createGuestSession(): Guest
    {
        $now = Carbon::now();

        // 1. Create the guest-customer row
        $customer = Customer::create([
            'id_shop_group'    => (int) config('prestashop.default_shop_group_id', 1),
            'id_shop'          => (int) config('prestashop.default_shop_id', 1),
            'id_gender'        => 0,
            'id_default_group' => (int) config('prestashop.default_customer_group_id', 3),
            'id_lang'          => (int) config('app.prestashop_lang', 1),
            'id_risk'          => 0,
            'firstname'        => 'Guest',
            'lastname'         => 'Visitor',
            'email'            => 'guest_' . Str::uuid() . '@guest.local',
            'passwd'           => Hash::make(Str::random(32)),
            'last_passwd_gen'  => $now,
            'secure_key'       => md5(Str::uuid()->toString()),
            'active'           => 1,
            'is_guest'         => true,
            'deleted'          => false,
            'date_add'         => $now,
            'date_upd'         => $now,
            'show_public_prices'       => 1,
            'max_payment_days'         => 0,
            'outstanding_allow_amount' => 0,
        ]);

        // 2. Create the guest row
        $guest = Guest::create([
            'id_customer' => $customer->id_customer,
        ]);

        // 3. Queue the cookie (SameSite=lax for same-site localhost, httpOnly for security)
        Cookie::queue(
            self::COOKIE_NAME,
            $guest->id_guest,
            self::COOKIE_MINUTES,
            '/',            // path
            null,           // domain
            false,          // secure (false for HTTP localhost)
            true,           // httpOnly
            false,          // raw
            'lax'           // sameSite (lax works for same-site localhost:3000 → localhost:8000)
        );

        return $guest;
    }
}

