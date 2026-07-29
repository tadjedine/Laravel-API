<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class HomesliderSlide
 * 
 * @property int $id_homeslider_slides
 * @property int $position
 * @property int $active
 *
 * @package App\Models
 */
class HomesliderSlide extends Model
{
	protected $table = 'ps_homeslider_slides';
	protected $primaryKey = 'id_homeslider_slides';
	public $timestamps = false;

	protected $casts = [
		'position' => 'int',
		'active' => 'int'
	];

	protected $fillable = [
		'position',
		'active'
	];

	

	
	public function homesliderSlidesLang(): HasMany
	{
		return $this->hasMany(HomesliderSlidesLang::class, 'id_homeslider_slides', 'id_homeslider_slides');
	}

	/**
	 * Single language row matching the store's configured language.
	 * Works exactly like Product::lang() in this project.
	 */
	public function lang(): HasOne
	{
		return $this->hasOne(HomesliderSlidesLang::class, 'id_homeslider_slides', 'id_homeslider_slides')
					->where('id_lang', config('app.prestashop_lang', 1));
	}

	// ─── Scopes ──────────────────────────────────────────────

	/**
	 * Only active slides.
	 * Usage: HomesliderSlide::active()->get()
	 */
	public function scopeActive(Builder $query): Builder
	{
		return $query->where('active', 1);
	}

	/**
	 * Order by position ascending.
	 * Usage: HomesliderSlide::ordered()->get()
	 */
	public function scopeOrdered(Builder $query): Builder
	{
		return $query->orderBy('position');
	}
}
