<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteMovie extends Model
{
	use HasFactory;
	protected $table = 'favorites_movies';
	protected $fillable = [
		'tmdb_id',
		'adult',
		'original_language',
		'original_title',
		'title',
		'overview',
		'backdrop_path',
		'poster_path',
		'release_date',
		'popularity',
		'vote_average',
		'vote_count',
		'genres',
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

	/**
	 * Scope para filtrar filmes por gêneros
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param array|int $genreIds IDs dos gêneros para filtrar
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeFilterByGenres($query, $genreIds)
	{
		if (empty($genreIds)) {
			return $query;
		}

		$genreIds = is_array($genreIds) ? $genreIds : [$genreIds];
		$genreIds = array_map('intval', $genreIds);

		return $query->where(function ($q) use ($genreIds) {
			foreach ($genreIds as $genreId) {
				$q->orWhereRaw(
					'JSON_SEARCH(genres, "one", ?, NULL, "$[*].id") IS NOT NULL',
					[$genreId]
				);
			}
		});
	}

	/**
	 * Scope para filtrar filmes por busca de texto
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string|null $searchQuery Texto para buscar no título
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeFilterBySearch($query, $searchQuery)
	{
		if (empty($searchQuery)) {
			return $query;
		}

		return $query->where('title', 'like', '%' . $searchQuery . '%');
	}
}
