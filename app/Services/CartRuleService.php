<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartCartRule;
use App\Models\CartRule;
use App\Models\CartRuleLang;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartRuleService
{
    // ── Public API ───────────────────────────────────────────────────────────

    /**
     * Find the customer's active cart, validate and attach the cart rule.
     */
    public function applyCode(int $customerId, string $code, int $idLang = 1): Cart
    {
        $cart = DB::transaction(function () use ($customerId, $code, $idLang) {
            // 1. Resolve cart rule (active scope includes date + quantity checks)
            $rule = CartRule::active()
                ->where('code', $code)
                ->first();

            if (! $rule) {
                throw new RuntimeException('Voucher code is invalid, expired, or no longer available.');
            }

            // 2. Customer restriction
            if ((int) $rule->id_customer > 0 && (int) $rule->id_customer !== $customerId) {
                throw new RuntimeException('This voucher code is not available for your account.');
            }

            // 3. Find customer cart (lock row for safety)
            $cart = $this->getActiveCartForCustomer($customerId);
            $lockedCart = Cart::query()->whereKey($cart->id_cart)->lockForUpdate()->firstOrFail();

            // 4. Already applied?
            $alreadyApplied = DB::table('ps_cart_cart_rule')
                ->where('id_cart', $lockedCart->id_cart)
                ->where('id_cart_rule', $rule->id_cart_rule)
                ->exists();

            if ($alreadyApplied) {
                throw new RuntimeException('This voucher has already been applied to your cart.');
            }

            // 5. Per-user usage limit
            if ((int) $rule->quantity_per_user > 0) {
                $usedCount = DB::table('ps_cart_cart_rule')
                    ->whereIn('id_cart', function ($sub) use ($customerId) {
                        $sub->select('id_cart')
                            ->from('ps_cart')
                            ->where('id_customer', $customerId);
                    })
                    ->where('id_cart_rule', $rule->id_cart_rule)
                    ->count();

                if ($usedCount >= (int) $rule->quantity_per_user) {
                    throw new RuntimeException('You have already used this voucher the maximum number of times.');
                }
            }

            // 6. Group restriction
            if ((int) $rule->group_restriction === 1) {
                $allowedGroups = $rule->cartRuleGroups()->pluck('id_group');

                if ($allowedGroups->isNotEmpty()) {
                    $customerInGroup = DB::table('ps_customer_group')
                        ->where('id_customer', $customerId)
                        ->whereIn('id_group', $allowedGroups)
                        ->exists();

                    if (! $customerInGroup) {
                        throw new RuntimeException('You are not eligible to use this voucher code.');
                    }
                }
            }

            // 7. Minimum amount
            if ((float) $rule->minimum_amount > 0) {
                $subtotal = $this->computeCartSubtotal($lockedCart);

                if ($subtotal < (float) $rule->minimum_amount) {
                    throw new RuntimeException(
                        sprintf(
                            'A minimum order amount of %s is required for this voucher.',
                            number_format((float) $rule->minimum_amount, 2)
                        )
                    );
                }
            }

            $lockedCart->cartRules()->attach($rule->id_cart_rule);

            $lockedCart->date_upd = Carbon::now();
            $lockedCart->save();

            return $lockedCart;
        });

        return $this->freshCart($cart->id_cart);
    }

    /**
     * Remove a cart rule from the customer's active cart.
     */
    public function removeCode(int $customerId, string $code): Cart
    {
        $cart = DB::transaction(function () use ($customerId, $code) {
            $rule = CartRule::query()
                ->where('code', $code)
                ->first();

            if (! $rule) {
                throw new RuntimeException('Voucher code not found.');
            }

            $cart = $this->getActiveCartForCustomer($customerId);
            $lockedCart = Cart::query()->whereKey($cart->id_cart)->lockForUpdate()->firstOrFail();

            $deleted = $lockedCart->cartRules()->detach($rule->id_cart_rule);

            if (! $deleted) {
                throw new RuntimeException('This voucher is not applied to your cart.');
            }

            $lockedCart->date_upd = Carbon::now();
            $lockedCart->save();

            return $lockedCart;
        });

        return $this->freshCart($cart->id_cart);
    }

    /**
     * Return all CartRule models applied to a cart, with their lang translation eager-loaded.
     */
    public function getAppliedRules(int $cartId): Collection
    {
        $cart = Cart::query()->findOrFail($cartId);

        return $cart->cartRules()->with('langs')->orderBy('priority')->get();
    }

    /**
     * Compute the combined discount for a cart.
     *
     * @return array{
     *   rules_applied: array,
     *   total_discount: float,
     *   free_shipping: bool,
     *   gift_products: array,
     *   subtotal_after_discount: float
     * }
     */
    public function computeDiscount(Cart $cart, int $idLang = 1): array
    {
        $rules = $cart->cartRules()->with('langs')->orderBy('priority')->get();

        if ($rules->isEmpty()) {
            $subtotal = $this->computeCartSubtotal($cart);

            return [
                'rules_applied'          => [],
                'total_discount'         => 0.0,
                'free_shipping'          => false,
                'gift_products'          => [],
                'subtotal_after_discount' => round($subtotal, 2),
            ];
        }

        $currentSubtotal = $this->computeCartSubtotal($cart);
        $totalDiscount   = 0.0;
        $freeShipping    = false;
        $giftProducts    = [];
        $rulesApplied    = [];

        // Pre-compute per-product line totals for product-specific discounts
        $productLineTotals = $this->computeProductLineTotals($cart);

        foreach ($rules as $rule) {
            $discountAmount    = 0.0;
            $langName          = $this->resolveLangName($rule, $idLang);
            $reductionProduct  = (int) $rule->reduction_product;

            if ((float) $rule->reduction_percent > 0) {
                if ($reductionProduct > 0) {
                    // Product-specific: apply % only to the targeted product's line total
                    $productTotal   = $productLineTotals[$reductionProduct] ?? 0.0;
                    $discountAmount = round(($rule->reduction_percent / 100) * $productTotal, 2);
                    // Cap: don't discount more than the remaining subtotal
                    $discountAmount  = min($discountAmount, $currentSubtotal);
                } else {
                    // Whole cart (reduction_product = 0): existing behavior
                    $discountAmount = round(($rule->reduction_percent / 100) * $currentSubtotal, 2);
                }

                $currentSubtotal = max(0.0, $currentSubtotal - $discountAmount);
                $totalDiscount  += $discountAmount;

            } elseif ((float) $rule->reduction_amount > 0) {
                if ($reductionProduct > 0) {
                    // Product-specific: cap the fixed amount to the product's line total
                    $productTotal   = $productLineTotals[$reductionProduct] ?? 0.0;
                    $discountAmount = min((float) $rule->reduction_amount, $productTotal, $currentSubtotal);
                } else {
                    // Whole cart: existing behavior
                    $discountAmount = min((float) $rule->reduction_amount, $currentSubtotal);
                }

                $currentSubtotal = max(0.0, $currentSubtotal - $discountAmount);
                $totalDiscount  += $discountAmount;
            }

            if ($rule->free_shipping) {
                $freeShipping = true;
            }

            if ((int) $rule->gift_product > 0) {
                $giftProducts[] = [
                    'id_product'           => (int) $rule->gift_product,
                    'id_product_attribute' => (int) $rule->gift_product_attribute,
                ];
            }

            $rulesApplied[] = [
                'id'              => (int) $rule->id_cart_rule,
                'code'            => $rule->code,
                'name'            => $langName,
                'discount_amount' => $discountAmount,
                'free_shipping'   => (bool) $rule->free_shipping,
                'gift_product_id' => (int) $rule->gift_product > 0 ? (int) $rule->gift_product : null,
                'reduction_product' => $reductionProduct > 0 ? $reductionProduct : null,
            ];
        }

        return [
            'rules_applied'          => $rulesApplied,
            'total_discount'         => round($totalDiscount, 2),
            'free_shipping'          => $freeShipping,
            'gift_products'          => $giftProducts,
            'subtotal_after_discount' => round($currentSubtotal, 2),
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function getActiveCartForCustomer(int $customerId): Cart
    {
        $cart = Cart::query()
            ->where('id_customer', $customerId)
            ->whereDoesntHave('order')
            ->orderByDesc('id_cart')
            ->first();

        if (! $cart) {
            throw new RuntimeException('No active cart found for this customer.');
        }

        return $cart;
    }

    private function computeCartSubtotal(Cart $cart): float
    {
        $rows = DB::table('ps_cart_product as cp')
            ->join('ps_product as p', 'p.id_product', '=', 'cp.id_product')
            ->where('cp.id_cart', $cart->id_cart)
            ->selectRaw('SUM(p.price * cp.quantity) as subtotal')
            ->value('subtotal');

        return (float) ($rows ?? 0.0);
    }

    /**
     * Compute the line total (price × quantity) for each product in the cart.
     * Returns an associative array: [ product_id => line_total ]
     */
    private function computeProductLineTotals(Cart $cart): array
    {
        $rows = DB::table('ps_cart_product as cp')
            ->join('ps_product as p', 'p.id_product', '=', 'cp.id_product')
            ->where('cp.id_cart', $cart->id_cart)
            ->selectRaw('cp.id_product, SUM(p.price * cp.quantity) as line_total')
            ->groupBy('cp.id_product')
            ->get();

        $totals = [];
        foreach ($rows as $row) {
            $totals[(int) $row->id_product] = (float) $row->line_total;
        }

        return $totals;
    }

    private function freshCart(int $cartId): Cart
    {
        return Cart::query()
            ->with(['products.product.images', 'products.combination', 'order', 'cartRules.langs'])
            ->findOrFail($cartId);
    }

    private function resolveLangName(CartRule $rule, int $idLang): string
    {
        /** @var CartRuleLang|null $lang */
        $lang = $rule->langs->firstWhere('id_lang', $idLang)
            ?? $rule->langs->first();

        return $lang?->name ?? $rule->code;
    }
}

