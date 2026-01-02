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
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function list(int $perPage = 15)
	{
		try {
			return $this->favoriteMovieModel->paginate($perPage);
		} catch (\Exception $e) {
			throw new \Exception('Erro ao listar filmes favoritos: ' . $e->getMessage());
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
