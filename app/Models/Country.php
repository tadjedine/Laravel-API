<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PsCountry
 * 
 * @property int $id_country
 * @property int $id_zone
 * @property int $id_currency
 * @property string $iso_code
 * @property int $call_prefix
 * @property int $active
 * @property bool $contains_states
 * @property bool $need_identification_number
 * @property bool $need_zip_code
 * @property string $zip_code_format
 * @property bool $display_tax_label
 *
 * @package App\Models
 */
class Country extends Model
{
	protected $table = 'ps_country';
	protected $primaryKey = 'id_country';
	public $timestamps = false;

	protected $casts = [
		'id_zone' => 'int',
		'id_currency' => 'int',
		'call_prefix' => 'int',
		'active' => 'int',
		'contains_states' => 'bool',
		'need_identification_number' => 'bool',
		'need_zip_code' => 'bool',
		'display_tax_label' => 'bool'
	];

	protected $fillable = [
		'id_zone',
		'id_currency',
		'iso_code',
		'call_prefix',
		'active',
		'contains_states',
		'need_identification_number',
		'need_zip_code',
		'zip_code_format',
		'display_tax_label'
	];
}
