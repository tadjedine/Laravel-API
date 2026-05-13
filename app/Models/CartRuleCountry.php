<?php

namespace App\Models;

class CartRuleCountry extends PrestashopModel
{
    protected $table = 'ps_cart_rule_country';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_cart_rule', 'id_country'];
}
