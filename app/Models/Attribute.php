<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class PsAttribute
 * 
 * @property int $id_attribute
 * @property int $id_attribute_group
 * @property string $color
 * @property int $position
 *
 * @package App\Models
 */
class Attribute extends PrestashopModel
{
	protected $table = 'ps_attribute';
	protected $primaryKey = 'id_attribute';
	public $timestamps = false;

	protected $casts = [
		'id_attribute_group' => 'int',
		'position' => 'int'
	];

	protected $fillable = [
		'id_attribute_group',
		'color',
		'position'
	];

	public function group(): BelongsTo
	{
		return $this->belongsTo(AttributeGroup::class, 'id_attribute_group', 'id_attribute_group');
	}

	public function lang(): HasOne
	{
		return $this->hasOne(AttributeLang::class, 'id_attribute', 'id_attribute')
					->where('id_lang', 1);
	}

	public function getNameAttribute(): ?string
	{
		return $this->lang?->name;
	}
}

