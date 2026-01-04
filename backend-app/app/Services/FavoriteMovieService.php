<?php

namespace App\Services;

use App\Models\FavoriteMovie;

class FavoriteMovieService
{
	public function __construct(
		protected FavoriteMovie $favoriteMovieModel,
	) {}

	/**
	 * Lista todos os filmes favoritos com paginação
	 * @param int $perPage Número de itens por página
	 * @param array $filters Filtros de busca
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function list(int $perPage = 15, array $filters = [])
	{
		try {
			$search = $filters['search'] ?? null;
			$genres = $filters['genres'] ?? null;

			$query = $this->favoriteMovieModel->query()
				->filterBySearch($search)
				->filterByGenres($genres);

			return $query->paginate($perPage);
		} catch (\Exception $e) {
			throw new \Exception('Erro ao listar filmes favoritos: ' . $e->getMessage());
		}
	}

	/**
	 * Adiciona um filme favorito por tmdb_id
	 * @param int $tmdb_id
	 * @return array
	 */
	public function addByTmdbId(int $tmdb_id)
	{
		try {
			/* VERIFICANDO SE O FILME JÁ ESTÁ NA BASE DE DADOS */
			$favoriteMovie = $this->favoriteMovieModel->where('tmdb_id', $tmdb_id)->first();
			if ($favoriteMovie) {
				throw new \Exception('Filme já favoritado: ' . $tmdb_id);
			}

			/* BUSCANDO O FILME NA API DO TMDB */
			$tmdbService = new TmdbService();
			$tmdbResponse = $tmdbService->getDetailsMovieById(['id' => $tmdb_id]);
			if (!$tmdbResponse) {
				throw new \Exception('Filme não encontrado na API: ' . $tmdb_id);
			}

			/* ADICIONANDO O FILME NA BASE DE DADOS */
			return $this->add([
				'tmdb_id' => $tmdb_id,
				'adult' => $tmdbResponse['adult'],
				'original_language' => $tmdbResponse['original_language'],
				'original_title' => $tmdbResponse['original_title'],
				'title' => $tmdbResponse['title'],
				'overview' => $tmdbResponse['overview'],
				'backdrop_path' => $tmdbResponse['backdrop_path'],
				'poster_path' => $tmdbResponse['poster_path'],
				'release_date' => $tmdbResponse['release_date'],
				'popularity' => $tmdbResponse['popularity'],
				'vote_average' => $tmdbResponse['vote_average'],
				'vote_count' => $tmdbResponse['vote_count'],
				'genres' => $tmdbResponse['genres'],
			]);
		} catch (\Exception $e) {
			throw new \Exception('Erro ao adicionar filme favorito: ' . $e->getMessage());
		}
	}

	/**
	 * Adiciona um filme favorito
	 * @param array $data
	 * @return array
	 */
	public function add(array $data)
	{
		try {
			return $this->favoriteMovieModel->create($data);
		} catch (\Exception $e) {
			throw new \Exception('Erro ao adicionar filme favorito: ' . $e->getMessage());
		}
	}

	/**
	 * Remove um filme favorito
	 * @param int $id
	 * @return bool
	 */
	public function remove(int $id)
	{
		try {
			$favoriteMovie = $this->favoriteMovieModel->find($id);
			if (!$favoriteMovie) {
				throw new \Exception('Filme favorito não encontrado: ' . $id);
			}
			return $favoriteMovie->delete();
		} catch (\Exception $e) {
			throw new \Exception('Erro ao remover filme favorito: ' . $e->getMessage());
		}
	}
}
