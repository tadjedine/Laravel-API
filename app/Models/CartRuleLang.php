<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartRuleLang extends PrestashopModel
{
    protected $table = 'ps_cart_rule_lang';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'id_cart_rule' => 'int',
        'id_lang'      => 'int',
    ];

    protected $fillable = [
        'id_cart_rule',
        'id_lang',
        'name',
        'description',
    ];

    public function cartRule(): BelongsTo
    {
        return $this->belongsTo(CartRule::class, 'id_cart_rule', 'id_cart_rule');
    }
}
