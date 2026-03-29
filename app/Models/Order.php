<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PsOrder
 * 
 * @property int $id_order
 * @property string|null $reference
 * @property int $id_shop_group
 * @property int $id_shop
 * @property int $id_carrier
 * @property int $id_lang
 * @property int $id_customer
 * @property int $id_cart
 * @property int $id_currency
 * @property int $id_address_delivery
 * @property int $id_address_invoice
 * @property int $current_state
 * @property string $secure_key
 * @property string $payment
 * @property float $conversion_rate
 * @property string|null $module
 * @property int $recyclable
 * @property int $gift
 * @property string|null $gift_message
 * @property bool $mobile_theme
 * @property float $total_discounts
 * @property float $total_discounts_tax_incl
 * @property float $total_discounts_tax_excl
 * @property float $total_paid
 * @property float $total_paid_tax_incl
 * @property float $total_paid_tax_excl
 * @property float $total_paid_real
 * @property float $total_products
 * @property float $total_products_wt
 * @property float $total_shipping
 * @property float $total_shipping_tax_incl
 * @property float $total_shipping_tax_excl
 * @property float $carrier_tax_rate
 * @property float $total_wrapping
 * @property float $total_wrapping_tax_incl
 * @property float $total_wrapping_tax_excl
 * @property bool $round_mode
 * @property bool $round_type
 * @property int $invoice_number
 * @property int $delivery_number
 * @property Carbon $invoice_date
 * @property Carbon $delivery_date
 * @property int $valid
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property string|null $note
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'ps_orders';
	protected $primaryKey = 'id_order';
	public $timestamps = false;

	protected $casts = [
		'id_shop_group' => 'int',
		'id_shop' => 'int',
		'id_carrier' => 'int',
		'id_lang' => 'int',
		'id_customer' => 'int',
		'id_cart' => 'int',
		'id_currency' => 'int',
		'id_address_delivery' => 'int',
		'id_address_invoice' => 'int',
		'current_state' => 'int',
		'conversion_rate' => 'float',
		'recyclable' => 'int',
		'gift' => 'int',
		'mobile_theme' => 'bool',
		'total_discounts' => 'float',
		'total_discounts_tax_incl' => 'float',
		'total_discounts_tax_excl' => 'float',
		'total_paid' => 'float',
		'total_paid_tax_incl' => 'float',
		'total_paid_tax_excl' => 'float',
		'total_paid_real' => 'float',
		'total_products' => 'float',
		'total_products_wt' => 'float',
		'total_shipping' => 'float',
		'total_shipping_tax_incl' => 'float',
		'total_shipping_tax_excl' => 'float',
		'carrier_tax_rate' => 'float',
		'total_wrapping' => 'float',
		'total_wrapping_tax_incl' => 'float',
		'total_wrapping_tax_excl' => 'float',
		'round_mode' => 'bool',
		'round_type' => 'bool',
		'invoice_number' => 'int',
		'delivery_number' => 'int',
		'invoice_date' => 'datetime',
		'delivery_date' => 'datetime',
		'valid' => 'int',
		'date_add' => 'datetime',
		'date_upd' => 'datetime'
	];

	protected $fillable = [
		'reference',
		'id_shop_group',
		'id_shop',
		'id_carrier',
		'id_lang',
		'id_customer',
		'id_cart',
		'id_currency',
		'id_address_delivery',
		'id_address_invoice',
		'current_state',
		'secure_key',
		'payment',
		'conversion_rate',
		'module',
		'recyclable',
		'gift',
		'gift_message',
		'mobile_theme',
		'total_discounts',
		'total_discounts_tax_incl',
		'total_discounts_tax_excl',
		'total_paid',
		'total_paid_tax_incl',
		'total_paid_tax_excl',
		'total_paid_real',
		'total_products',
		'total_products_wt',
		'total_shipping',
		'total_shipping_tax_incl',
		'total_shipping_tax_excl',
		'carrier_tax_rate',
		'total_wrapping',
		'total_wrapping_tax_incl',
		'total_wrapping_tax_excl',
		'round_mode',
		'round_type',
		'invoice_number',
		'delivery_number',
		'invoice_date',
		'delivery_date',
		'valid',
		'date_add',
		'date_upd',
		'note'
	];

	Public function customer ()
	{
		return $this->belongsTo(Customer::class, 'id_customer');
	}

	public function products() {
    	return $this->belongsToMany(Product::class, 'ps_order_detail', 'id_order', 'product_id');
	}

}
