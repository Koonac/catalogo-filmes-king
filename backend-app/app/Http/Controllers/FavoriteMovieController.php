<?php

namespace App\Http\Controllers;

use App\Services\FavoriteMovieService;
use Illuminate\Http\Request;

class FavoriteMovieController extends Controller
{
	public function __construct(
		protected FavoriteMovieService $favoriteMovieService,
	) {}

	/**
	 * Lista todos os filmes favoritos com paginação
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function list(Request $request)
	{
		try {
			$perPage = $request->query('per_page', 15);
			$favoriteMovies = $this->favoriteMovieService->list($perPage);
			/* RETORNANDO A RESPOSTA EM JSON */
			return response()->json([
				'status' => 'success',
				'data' => $favoriteMovies->items(),
				'pagination' => [
					'current_page' => $favoriteMovies->currentPage(),
					'per_page' => $favoriteMovies->perPage(),
					'total' => $favoriteMovies->total(),
					'last_page' => $favoriteMovies->lastPage(),
					'from' => $favoriteMovies->firstItem(),
					'to' => $favoriteMovies->lastItem(),
				],
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Erro ao listar filmes favoritos',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Adiciona um filme favorito
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addByTmdbId(Request $request)
	{
		try {
			$request->validate([
				'tmdb_id' => 'required|integer',
			], [
				'tmdb_id.required' => 'O campo tmdb_id é obrigatório',
			]);

			$favoriteMovie = $this->favoriteMovieService->addByTmdbId($request->tmdb_id);

			return response()->json([
				'status' => 'success',
				'message' => 'Filme favorito adicionado com sucesso',
				'data' => $favoriteMovie,
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Erro ao adicionar filme favorito',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Remove um filme favorito
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function remove(Request $request)
	{
		try {
			$request->validate([
				'id' => 'required|integer'
			], [
				'id.required' => 'O campo id é obrigatório',
			]);

			$this->favoriteMovieService->remove($request->id);

			return response()->json([
				'status' => 'success',
				'message' => 'Filme favorito removido com sucesso',
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Erro ao remover filme favorito',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
