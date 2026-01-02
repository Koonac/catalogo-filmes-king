<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteMovie extends Model
{
	protected $table = 'favorites_movies';
	protected $fillable = [
		'tmdb_id',
		'adult',
		'original_language',
		'original_title',
		'title',
		'overview',
		'backdrop_path',
	];

	protected $casts = [
		'adult' => 'boolean',
		'genres' => 'array',
	];

	public function getGenresAttribute($value)
	{
		return json_decode($value, true);
	}

	public function setGenresAttribute($value)
	{
		$this->attributes['genres'] = json_encode($value);
	}

	public function getBackdropPathAttribute($value)
	{
		if (!$value) {
			return null;
		}

		/* ANALISANDO SE TEM BARRA NO INICIO DA URL */
		if (str_starts_with($value, '/')) {
			$value = substr($value, 1);
		}

		/* RETORNANDO A URL COMPLETA */
		return 'https://image.tmdb.org/t/p/w500/' . $value;
	}

	public function getPosterPathAttribute($value)
	{
		if (!$value) {
			return null;
		}

		/* ANALISANDO SE TEM BARRA NO INICIO DA URL */
		if (str_starts_with($value, '/')) {
			$value = substr($value, 1);
		}

		return 'https://image.tmdb.org/t/p/w500/' . $value;
	}
}
