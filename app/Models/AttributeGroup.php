<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class PsAttributeGroup
 * 
 * @property int $id_attribute_group
 * @property bool $is_color_group
 * @property string $group_type
 * @property int $position
 *
 * @package App\Models
 */
class AttributeGroup extends PrestashopModel
{
	protected $table = 'ps_attribute_group';
	protected $primaryKey = 'id_attribute_group';
	public $timestamps = false;

	protected $casts = [
		'is_color_group' => 'bool',
		'position' => 'int'
	];

	protected $fillable = [
		'is_color_group',
		'group_type',
		'position'
	];

	public function attributes(): HasMany
	{
		return $this->hasMany(Attribute::class, 'id_attribute_group', 'id_attribute_group')
					->orderBy('position');
	}

	public function lang(): HasOne
	{
		return $this->hasOne(AttributeGroupLang::class, 'id_attribute_group', 'id_attribute_group')
					->where('id_lang', 1);
	}

	public function getNameAttribute(): ?string
	{
		return $this->lang?->name;
	}

	public function getPublicNameAttribute(): ?string
	{
		return $this->lang?->public_name;
	}
}

