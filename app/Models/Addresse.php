<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PsAddress
 * 
 * @property int $id_address
 * @property int $id_country
 * @property int|null $id_state
 * @property int $id_customer
 * @property int $id_manufacturer
 * @property int $id_supplier
 * @property int $id_warehouse
 * @property string $alias
 * @property string|null $company
 * @property string $lastname
 * @property string $firstname
 * @property string $address1
 * @property string|null $address2
 * @property string|null $postcode
 * @property string $city
 * @property string|null $other
 * @property string|null $phone
 * @property string|null $phone_mobile
 * @property string|null $vat_number
 * @property string|null $dni
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property int $active
 * @property int $deleted
 *
 * @package App\Models
 */
class PsAddress extends Model
{
	protected $table = 'ps_address';
	protected $primaryKey = 'id_address';
	public $timestamps = false;

	protected $casts = [
		'id_country' => 'int',
		'id_state' => 'int',
		'id_customer' => 'int',
		'id_manufacturer' => 'int',
		'id_supplier' => 'int',
		'id_warehouse' => 'int',
		'date_add' => 'datetime',
		'date_upd' => 'datetime',
		'active' => 'int',
		'deleted' => 'int'
	];

	protected $fillable = [
		'id_country',
		'id_state',
		'id_customer',
		'id_manufacturer',
		'id_supplier',
		'id_warehouse',
		'alias',
		'company',
		'lastname',
		'firstname',
		'address1',
		'address2',
		'postcode',
		'city',
		'other',
		'phone',
		'phone_mobile',
		'vat_number',
		'dni',
		'date_add',
		'date_upd',
		'active',
		'deleted'
	];
}
