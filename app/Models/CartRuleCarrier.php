<?php

namespace App\Models;

class CartRuleCarrier extends PrestashopModel
{
    protected $table = 'ps_cart_rule_carrier';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_cart_rule', 'id_carrier'];
}
