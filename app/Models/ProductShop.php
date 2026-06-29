<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PsProductShop
 * 
 * @property int $id_product
 * @property int $id_shop
 * @property int|null $id_category_default
 * @property int $id_tax_rules_group
 * @property int $on_sale
 * @property int $online_only
 * @property float $ecotax
 * @property int $minimal_quantity
 * @property int|null $low_stock_threshold
 * @property bool $low_stock_alert
 * @property float $price
 * @property float $wholesale_price
 * @property string|null $unity
 * @property float $unit_price
 * @property float $unit_price_ratio
 * @property float $additional_shipping_cost
 * @property int $customizable
 * @property int $uploadable_files
 * @property int $text_fields
 * @property int $active
 * @property string $redirect_type
 * @property int $id_type_redirected
 * @property bool $available_for_order
 * @property Carbon|null $available_date
 * @property bool $show_condition
 * @property string $condition
 * @property bool $show_price
 * @property bool $indexed
 * @property string $visibility
 * @property int|null $cache_default_attribute
 * @property bool $advanced_stock_management
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property int $pack_stock_type
 *
 * @package App\Models
 */
class ProductShop extends Model
{
	protected $table = 'ps_product_shop';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_product' => 'int',
		'id_shop' => 'int',
		'id_category_default' => 'int',
		'id_tax_rules_group' => 'int',
		'on_sale' => 'int',
		'online_only' => 'int',
		'ecotax' => 'float',
		'minimal_quantity' => 'int',
		'low_stock_threshold' => 'int',
		'low_stock_alert' => 'bool',
		'price' => 'float',
		'wholesale_price' => 'float',
		'unit_price' => 'float',
		'unit_price_ratio' => 'float',
		'additional_shipping_cost' => 'float',
		'customizable' => 'int',
		'uploadable_files' => 'int',
		'text_fields' => 'int',
		'active' => 'int',
		'id_type_redirected' => 'int',
		'available_for_order' => 'bool',
		'available_date' => 'datetime',
		'show_condition' => 'bool',
		'show_price' => 'bool',
		'indexed' => 'bool',
		'cache_default_attribute' => 'int',
		'advanced_stock_management' => 'bool',
		'date_add' => 'datetime',
		'date_upd' => 'datetime',
		'pack_stock_type' => 'int'
	];

	protected $fillable = [
		'id_category_default',
		'id_tax_rules_group',
		'on_sale',
		'online_only',
		'ecotax',
		'minimal_quantity',
		'low_stock_threshold',
		'low_stock_alert',
		'price',
		'wholesale_price',
		'unity',
		'unit_price',
		'unit_price_ratio',
		'additional_shipping_cost',
		'customizable',
		'uploadable_files',
		'text_fields',
		'active',
		'redirect_type',
		'id_type_redirected',
		'available_for_order',
		'available_date',
		'show_condition',
		'condition',
		'show_price',
		'indexed',
		'visibility',
		'cache_default_attribute',
		'advanced_stock_management',
		'date_add',
		'date_upd',
		'pack_stock_type'
	];
}
