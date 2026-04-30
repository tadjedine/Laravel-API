<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PsCartProduct
 *
 * @property int $id_cart
 * @property int $id_product
 * @property int $id_address_delivery
 * @property int $id_shop
 * @property int $id_product_attribute
 * @property int $id_customization
 * @property int $quantity
 * @property Carbon $date_add
 */
class CartProduct extends PrestashopModel
{
    protected $table = 'ps_cart_product';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'id_cart' => 'int',
        'id_product' => 'int',
        'id_address_delivery' => 'int',
        'id_shop' => 'int',
        'id_product_attribute' => 'int',
        'id_customization' => 'int',
        'quantity' => 'int',
        'date_add' => 'datetime',
    ];

    protected $fillable = [
        'id_cart',
        'id_product',
        'id_address_delivery',
        'id_shop',
        'id_product_attribute',
        'id_customization',
        'quantity',
        'date_add',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'id_cart', 'id_cart');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function combination(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'id_product_attribute', 'id_product_attribute');
    }

    public function productAttribute(): BelongsTo
    {
        return $this->combination();
    }
}
