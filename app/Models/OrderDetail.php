<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PsOrderDetail
 *
 * @property int $id_order_detail
 * @property int $id_order
 * @property int|null $id_order_invoice
 * @property int|null $id_warehouse
 * @property int $id_shop
 * @property int $product_id
 * @property int|null $product_attribute_id
 * @property int|null $id_customization
 * @property string $product_name
 * @property int $product_quantity
 * @property int $product_quantity_in_stock
 * @property int $product_quantity_refunded
 * @property int $product_quantity_return
 * @property int $product_quantity_reinjected
 * @property float $product_price
 * @property float $reduction_percent
 * @property float $reduction_amount
 * @property float $reduction_amount_tax_incl
 * @property float $reduction_amount_tax_excl
 * @property float $group_reduction
 * @property float $product_quantity_discount
 * @property string|null $product_ean13
 * @property string|null $product_isbn
 * @property string|null $product_upc
 * @property string|null $product_mpn
 * @property string|null $product_reference
 * @property string|null $product_supplier_reference
 * @property float $product_weight
 * @property int|null $id_tax_rules_group
 * @property int $tax_computation_method
 * @property string $tax_name
 * @property float $tax_rate
 * @property float $ecotax
 * @property float $ecotax_tax_rate
 * @property bool $discount_quantity_applied
 * @property string|null $download_hash
 * @property int|null $download_nb
 * @property Carbon|null $download_deadline
 * @property float $total_price_tax_incl
 * @property float $total_price_tax_excl
 * @property float $unit_price_tax_incl
 * @property float $unit_price_tax_excl
 * @property float $total_shipping_price_tax_incl
 * @property float $total_shipping_price_tax_excl
 * @property float $purchase_supplier_price
 * @property float $original_product_price
 * @property float $original_wholesale_price
 * @property float $total_refunded_tax_excl
 * @property float $total_refunded_tax_incl
 */
class OrderDetail extends Model
{
    protected $table = 'ps_order_detail';

    protected $primaryKey = 'id_order_detail';

    public $timestamps = false;

    protected $casts = [
        'id_order' => 'int',
        'id_order_invoice' => 'int',
        'id_warehouse' => 'int',
        'id_shop' => 'int',
        'product_id' => 'int',
        'product_attribute_id' => 'int',
        'id_customization' => 'int',
        'product_quantity' => 'int',
        'product_quantity_in_stock' => 'int',
        'product_quantity_refunded' => 'int',
        'product_quantity_return' => 'int',
        'product_quantity_reinjected' => 'int',
        'product_price' => 'float',
        'reduction_percent' => 'float',
        'reduction_amount' => 'float',
        'reduction_amount_tax_incl' => 'float',
        'reduction_amount_tax_excl' => 'float',
        'group_reduction' => 'float',
        'product_quantity_discount' => 'float',
        'product_weight' => 'float',
        'id_tax_rules_group' => 'int',
        'tax_computation_method' => 'int',
        'tax_rate' => 'float',
        'ecotax' => 'float',
        'ecotax_tax_rate' => 'float',
        'discount_quantity_applied' => 'bool',
        'download_nb' => 'int',
        'download_deadline' => 'datetime',
        'total_price_tax_incl' => 'float',
        'total_price_tax_excl' => 'float',
        'unit_price_tax_incl' => 'float',
        'unit_price_tax_excl' => 'float',
        'total_shipping_price_tax_incl' => 'float',
        'total_shipping_price_tax_excl' => 'float',
        'purchase_supplier_price' => 'float',
        'original_product_price' => 'float',
        'original_wholesale_price' => 'float',
        'total_refunded_tax_excl' => 'float',
        'total_refunded_tax_incl' => 'float',
    ];

    protected $fillable = [
        'id_order',
        'id_order_invoice',
        'id_warehouse',
        'id_shop',
        'product_id',
        'product_attribute_id',
        'id_customization',
        'product_name',
        'product_quantity',
        'product_quantity_in_stock',
        'product_quantity_refunded',
        'product_quantity_return',
        'product_quantity_reinjected',
        'product_price',
        'reduction_percent',
        'reduction_amount',
        'reduction_amount_tax_incl',
        'reduction_amount_tax_excl',
        'group_reduction',
        'product_quantity_discount',
        'product_ean13',
        'product_isbn',
        'product_upc',
        'product_mpn',
        'product_reference',
        'product_supplier_reference',
        'product_weight',
        'id_tax_rules_group',
        'tax_computation_method',
        'tax_name',
        'tax_rate',
        'ecotax',
        'ecotax_tax_rate',
        'discount_quantity_applied',
        'download_hash',
        'download_nb',
        'download_deadline',
        'total_price_tax_incl',
        'total_price_tax_excl',
        'unit_price_tax_incl',
        'unit_price_tax_excl',
        'total_shipping_price_tax_incl',
        'total_shipping_price_tax_excl',
        'purchase_supplier_price',
        'original_product_price',
        'original_wholesale_price',
        'total_refunded_tax_excl',
        'total_refunded_tax_incl',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id', 'id_product_attribute');
    }
}
