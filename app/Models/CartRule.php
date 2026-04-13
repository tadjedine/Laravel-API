<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PsCartRule
 * 
 * @property int $id_cart_rule
 * @property int $id_customer
 * @property Carbon $date_from
 * @property Carbon $date_to
 * @property string|null $description
 * @property int|null $quantity
 * @property int|null $quantity_per_user
 * @property int $priority
 * @property int $partial_use
 * @property string $code
 * @property float $minimum_amount
 * @property bool $minimum_amount_tax
 * @property int $minimum_amount_currency
 * @property bool $minimum_amount_shipping
 * @property int $country_restriction
 * @property int $carrier_restriction
 * @property int $group_restriction
 * @property int $cart_rule_restriction
 * @property int $product_restriction
 * @property int $shop_restriction
 * @property bool $free_shipping
 * @property float $reduction_percent
 * @property float $reduction_amount
 * @property int $reduction_tax
 * @property int $reduction_currency
 * @property int $reduction_product
 * @property int $reduction_exclude_special
 * @property int $gift_product
 * @property int $gift_product_attribute
 * @property int $highlight
 * @property int $active
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property int|null $id_cart_rule_type
 * @property int $minimum_product_quantity
 *
 * @package App\Models
 */
class CartRule extends Model
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
		'minimum_product_quantity' => 'int'
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
		'minimum_product_quantity'
	];

	public function carts() {
    	return $this->belongsToMany(Cart::class, 'ps_cart_cart_rule', 'id_cart_rule', 'id_cart');
	}
}
