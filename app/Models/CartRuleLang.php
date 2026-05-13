<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class CartRule
 *
 * @package App\Models
 */
class CartRule extends PrestashopModel
{
    protected $table = 'ps_cart_rule';
    protected $primaryKey = 'id_cart_rule';
    public $timestamps = false;

    protected $casts = [
        'id_customer' => 'int',
        'date_from' => 'datetime',
        'date_to' => 'datetime',
        'quantity' => 'int',
        'quantity_per_user' => 'int',
        'priority' => 'int',
        'partial_use' => 'int',
        'minimum_amount' => 'float',
        'minimum_amount_tax' => 'bool',
        'minimum_amount_currency' => 'int',
        'minimum_amount_shipping' => 'bool',
        'country_restriction' => 'int',
        'carrier_restriction' => 'int',
        'group_restriction' => 'int',
        'cart_rule_restriction' => 'int',
        'product_restriction' => 'int',
        'shop_restriction' => 'int',
        'free_shipping' => 'bool',
        'reduction_percent' => 'float',
        'reduction_amount' => 'float',
        'reduction_tax' => 'int',
        'reduction_currency' => 'int',
        'reduction_product' => 'int',
        'reduction_exclude_special' => 'int',
        'gift_product' => 'int',
        'gift_product_attribute' => 'int',
        'highlight' => 'int',
        'active' => 'int',
        'date_add' => 'datetime',
        'date_upd' => 'datetime',
        'id_cart_rule_type' => 'int',
        'minimum_product_quantity' => 'int',
    ];

    protected $fillable = [
        'id_customer',
        'date_from',
        'date_to',
        'description',
        'quantity',
        'quantity_per_user',
        'priority',
        'partial_use',
        'code',
        'minimum_amount',
        'minimum_amount_tax',
        'minimum_amount_currency',
        'minimum_amount_shipping',
        'country_restriction',
        'carrier_restriction',
        'group_restriction',
        'cart_rule_restriction',
        'product_restriction',
        'shop_restriction',
        'free_shipping',
        'reduction_percent',
        'reduction_amount',
        'reduction_tax',
        'reduction_currency',
        'reduction_product',
        'reduction_exclude_special',
        'gift_product',
        'gift_product_attribute',
        'highlight',
        'active',
        'date_add',
        'date_upd',
        'id_cart_rule_type',
        'minimum_product_quantity',
    ];

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Scope: active=1, within validity window, still has remaining global uses.
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->where('active', 1)
            ->where('quantity', '>', 0)
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('date_from')->orWhere('date_from', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('date_to')->orWhere('date_to', '>=', $now);
            });
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'ps_cart_cart_rule', 'id_cart_rule', 'id_cart');
    }

    public function langs(): HasMany
    {
        return $this->hasMany(CartRuleLang::class, 'id_cart_rule', 'id_cart_rule');
    }

    public function cartRuleCountries(): HasMany
    {
        return $this->hasMany(CartRuleCountry::class, 'id_cart_rule', 'id_cart_rule');
    }

    public function cartRuleCarriers(): HasMany
    {
        return $this->hasMany(CartRuleCarrier::class, 'id_cart_rule', 'id_cart_rule');
    }

    public function cartRuleGroups(): HasMany
    {
        return $this->hasMany(CartRuleGroup::class, 'id_cart_rule', 'id_cart_rule');
    }
}
