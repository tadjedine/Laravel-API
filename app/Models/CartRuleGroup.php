<?php

namespace App\Models;

class CartRuleGroup extends PrestashopModel
{
    protected $table = 'ps_cart_rule_group';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_cart_rule', 'id_group'];
}
