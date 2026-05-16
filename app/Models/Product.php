<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PsProduct
 *
 * @property int $id_product
 * @property int|null $id_supplier
 * @property int|null $id_manufacturer
 * @property int|null $id_category_default
 * @property int $id_shop_default
 * @property int $id_tax_rules_group
 * @property int $on_sale
 * @property int $online_only
 * @property string|null $ean13
 * @property string|null $isbn
 * @property string|null $upc
 * @property string|null $mpn
 * @property float $ecotax
 * @property int $quantity
 * @property int $minimal_quantity
 * @property int|null $low_stock_threshold
 * @property bool $low_stock_alert
 * @property float $price
 * @property float $wholesale_price
 * @property string|null $unity
 * @property float $unit_price
 * @property float $unit_price_ratio
 * @property float $additional_shipping_cost
 * @property string|null $reference
 * @property string|null $supplier_reference
 * @property string $location
 * @property float $width
 * @property float $height
 * @property float $depth
 * @property float $weight
 * @property int $out_of_stock
 * @property int $additional_delivery_times
 * @property bool|null $quantity_discount
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
 * @property bool $cache_is_pack
 * @property bool $cache_has_attachments
 * @property bool $is_virtual
 * @property int|null $cache_default_attribute
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property bool $advanced_stock_management
 * @property int $pack_stock_type
 * @property int $state
 * @property string $product_type
 *
 * @package App\Models
 */
class Product extends PrestashopModel
{
	protected $table = 'ps_product';
	protected $primaryKey = 'id_product';
	public $timestamps = false;

	protected $casts = [
		'id_supplier' => 'int',
		'id_manufacturer' => 'int',
		'id_category_default' => 'int',
		'id_shop_default' => 'int',
		'id_tax_rules_group' => 'int',
		'on_sale' => 'int',
		'online_only' => 'int',
		'ecotax' => 'float',
		'quantity' => 'int',
		'minimal_quantity' => 'int',
		'low_stock_threshold' => 'int',
		'low_stock_alert' => 'bool',
		'price' => 'float',
		'wholesale_price' => 'float',
		'unit_price' => 'float',
		'unit_price_ratio' => 'float',
		'additional_shipping_cost' => 'float',
		'width' => 'float',
		'height' => 'float',
		'depth' => 'float',
		'weight' => 'float',
		'out_of_stock' => 'int',
		'additional_delivery_times' => 'int',
		'quantity_discount' => 'bool',
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
		'cache_is_pack' => 'bool',
		'cache_has_attachments' => 'bool',
		'is_virtual' => 'bool',
		'cache_default_attribute' => 'int',
		'date_add' => 'datetime',
		'date_upd' => 'datetime',
		'advanced_stock_management' => 'bool',
		'pack_stock_type' => 'int',
		'state' => 'int'
	];

	protected $fillable = [
		'id_supplier',
		'id_manufacturer',
		'id_category_default',
		'id_shop_default',
		'id_tax_rules_group',
		'on_sale',
		'online_only',
		'ean13',
		'isbn',
		'upc',
		'mpn',
		'ecotax',
		'quantity',
		'minimal_quantity',
		'low_stock_threshold',
		'low_stock_alert',
		'price',
		'wholesale_price',
		'unity',
		'unit_price',
		'unit_price_ratio',
		'additional_shipping_cost',
		'reference',
		'supplier_reference',
		'location',
		'width',
		'height',
		'depth',
		'weight',
		'out_of_stock',
		'additional_delivery_times',
		'quantity_discount',
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
		'cache_is_pack',
		'cache_has_attachments',
		'is_virtual',
		'cache_default_attribute',
		'date_add',
		'date_upd',
		'advanced_stock_management',
		'pack_stock_type',
		'state',
		'product_type'
	];


	public function categories() {
    	return $this->belongsToMany(Category::class, 'ps_category_product', 'id_product', 'id_category');
	}

	public function productAttribute() : HasMany
	{
		return $this->hasMany(ProductAttribute::class);
	}

	//Cart line items that reference this product.
	public function cartProducts(): HasMany
	{
		return $this->hasMany(CartProduct::class, 'id_product', 'id_product');
	}


	public function carts() {
    	return $this->belongsToMany(Cart::class, 'ps_cart_product', 'id_product', 'id_cart');
	}


	public function orders() {
    	return $this->belongsToMany(Order::class, 'ps_order_detail', 'product_id', 'id_order');
	}

	public function images()
	{
		return $this->hasMany(ProductImage::class,'id_product')
					->OrderBy('position');
	}

	public function lang(): HasOne
	{
		return $this->hasOne(ProductLang::class, 'id_product', 'id_product')
					->where('id_lang', 1)
					->where('id_shop', 1);
	}

	public function coverImage(): HasOne
	{
		return $this->hasOne(ProductImage::class, 'id_product', 'id_product')
					->where('cover', 1);
	}

	public function getNameAttribute(): ?string
	{
		return $this->lang?->name;
	}

	public function getDescriptionAttribute(): ?string
	{
		return $this->lang?->description;
	}

	public function getDescriptionShortAttribute(): ?string
	{
		return $this->lang?->description_short;
	}

	public function getLinkRewriteAttribute(): ?string
	{
		return $this->lang?->link_rewrite;
	}
}
