<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartCartRule extends PrestashopModel
{
    protected $table = 'ps_cart_cart_rule';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'id_cart'          => 'int',
        'id_cart_rule'     => 'int',
        'id_order_invoice' => 'int',
    ];

    protected $fillable = [
        'id_cart',
        'id_cart_rule',
        'id_order_invoice',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'id_cart', 'id_cart');
    }

    public function cartRule(): BelongsTo
    {
        return $this->belongsTo(CartRule::class, 'id_cart_rule', 'id_cart_rule');
    }
}
