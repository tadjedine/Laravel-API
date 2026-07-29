<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class HomesliderSlidesLang
 * 
 * @property int $id_homeslider_slides
 * @property int $id_lang
 * @property string $title
 * @property string $description
 * @property string $legend
 * @property string $url
 * @property string $image
 *
 * @package App\Models
 */
class HomesliderSlidesLang extends Model
{
	protected $table = 'ps_homeslider_slides_lang';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_homeslider_slides' => 'int',
		'id_lang' => 'int'
	];

	protected $fillable = [
		'title',
		'description',
		'legend',
		'url',
		'image'
	];

	public function homesliderSlide(): BelongsTo
	{
		return $this->belongsTo(HomesliderSlide::class, 'id_homeslider_slides', 'id_homeslider_slides');
	}
}
