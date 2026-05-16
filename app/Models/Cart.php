<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany ;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PsCart
 *
 * @property int $id_cart
 * @property int $id_shop_group
 * @property int $id_shop
 * @property int $id_carrier
 * @property string $delivery_option
 * @property int $id_lang
 * @property int $id_address_delivery
 * @property int $id_address_invoice
 * @property int $id_currency
 * @property int $id_customer
 * @property int $id_guest
 * @property string $secure_key
 * @property int $recyclable
 * @property int $gift
 * @property string|null $gift_message
 * @property bool $mobile_theme
 * @property int $allow_seperated_package
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property string|null $checkout_session_data
 */
class Cart extends PrestashopModel
{
    protected $table = 'ps_cart';

    protected $primaryKey = 'id_cart';

    public $timestamps = false;

    protected $casts = [
        'id_shop_group' => 'int',
        'id_shop' => 'int',
        'id_carrier' => 'int',
        'id_lang' => 'int',
        'id_address_delivery' => 'int',
        'id_address_invoice' => 'int',
        'id_currency' => 'int',
        'id_customer' => 'int',
        'id_guest' => 'int',
        'recyclable' => 'int',
        'gift' => 'int',
        'mobile_theme' => 'bool',
        'allow_seperated_package' => 'int',
        'date_add' => 'datetime',
        'date_upd' => 'datetime',
    ];

    protected $fillable = [
        'id_shop_group',
        'id_shop',
        'id_carrier',
        'delivery_option',
        'id_lang',
        'id_address_delivery',
        'id_address_invoice',
        'id_currency',
        'id_customer',
        'id_guest',
        'secure_key',
        'recyclable',
        'gift',
        'gift_message',
        'mobile_theme',
        'allow_seperated_package',
        'date_add',
        'date_upd',
        'checkout_session_data',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // To get products models of this cart directly
    public function productModels(): BelongsToMany
    {
    return $this->belongsToMany(Product::class, 'ps_cart_product', 'id_cart', 'id_product')
        ->withPivot(['id_product_attribute', 'id_customization', 'quantity', 'id_shop', 'id_address_delivery', 'date_add']);
    }

    public function products(): HasMany
    {
        return $this->hasMany(CartProduct::class, 'id_cart', 'id_cart');
    }

    public function items(): HasMany
    {
        return $this->products();
    }

     public function cartRules(): BelongsToMany
    {
        return $this->belongsToMany(CartRule::class, 'ps_cart_cart_rule', 'id_cart', 'id_cart_rule');
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'id_cart', 'id_cart');
    }

    // public function cartRules(): HasMany
    // {
    //     return $this->hasMany(CartCartRule::class, 'id_cart', 'id_cart'); // if you need promo codes
    // }

}
