<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PsGuest
 * 
 * @property int $id_guest
 * @property int|null $id_operating_system
 * @property int|null $id_web_browser
 * @property int|null $id_customer
 * @property bool|null $javascript
 * @property int|null $screen_resolution_x
 * @property int|null $screen_resolution_y
 * @property int|null $screen_color
 * @property bool|null $sun_java
 * @property bool|null $adobe_flash
 * @property bool|null $adobe_director
 * @property bool|null $apple_quicktime
 * @property bool|null $real_player
 * @property bool|null $windows_media
 * @property string|null $accept_language
 * @property bool $mobile_theme
 *
 * @package App\Models
 */
class Guest extends PrestashopModel
{
	protected $table = 'ps_guest';
	protected $primaryKey = 'id_guest';
	public $timestamps = false;

	protected $casts = [
		'id_operating_system' => 'int',
		'id_web_browser' => 'int',
		'id_customer' => 'int',
		'javascript' => 'bool',
		'screen_resolution_x' => 'int',
		'screen_resolution_y' => 'int',
		'screen_color' => 'int',
		'sun_java' => 'bool',
		'adobe_flash' => 'bool',
		'adobe_director' => 'bool',
		'apple_quicktime' => 'bool',
		'real_player' => 'bool',
		'windows_media' => 'bool',
		'mobile_theme' => 'bool'
	];

	protected $fillable = [
		'id_operating_system',
		'id_web_browser',
		'id_customer',
		'javascript',
		'screen_resolution_x',
		'screen_resolution_y',
		'screen_color',
		'sun_java',
		'adobe_flash',
		'adobe_director',
		'apple_quicktime',
		'real_player',
		'windows_media',
		'accept_language',
		'mobile_theme'
	];


	public function carts(): HasMany
	{
    return $this->hasMany(Cart::class, 'id_guest', 'id_guest');
	}
}
