<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PsCategory
 * 
 * @property int $id_category
 * @property int $id_parent
 * @property int $id_shop_default
 * @property int $level_depth
 * @property int $nleft
 * @property int $nright
 * @property int $active
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property string $redirect_type
 * @property int $id_type_redirected
 * @property int $position
 * @property bool $is_root_category
 *
 * @package App\Models
 */
class Category extends Model
{
	protected $table = 'ps_category';
	protected $primaryKey = 'id_category';
	public $timestamps = false;

	protected $casts = [
		'id_parent' => 'int',
		'id_shop_default' => 'int',
		'level_depth' => 'int',
		'nleft' => 'int',
		'nright' => 'int',
		'active' => 'int',
		'date_add' => 'datetime',
		'date_upd' => 'datetime',
		'id_type_redirected' => 'int',
		'position' => 'int',
		'is_root_category' => 'bool'
	];

	protected $fillable = [
		'id_parent',
		'id_shop_default',
		'level_depth',
		'nleft',
		'nright',
		'active',
		'date_add',
		'date_upd',
		'redirect_type',
		'id_type_redirected',
		'position',
		'is_root_category'
	];
}
