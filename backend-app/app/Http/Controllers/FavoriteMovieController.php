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
	public function add(Request $request)
	{
		try {
			$request->validate([
				'tmdb_id' => 'required|integer',
				'adult' => 'required|boolean',
				'original_language' => 'required|string',
				'original_title' => 'required|string',
				'title' => 'required|string',
				'overview' => 'required|string',
				'backdrop_path' => 'required|string',
				'poster_path' => 'required|string',
				'release_date' => 'required|string',
				'popularity' => 'required|float',
				'vote_average' => 'required|float',
				'vote_count' => 'required|integer',
				'genres' => 'required|array',
			], [
				'tmdb_id.required' => 'O campo tmdb_id é obrigatório',
				'adult.required' => 'O campo adult é obrigatório',
				'original_language.required' => 'O campo original_language é obrigatório',
				'original_title.required' => 'O campo original_title é obrigatório',
				'title.required' => 'O campo title é obrigatório',
				'overview.required' => 'O campo overview é obrigatório',
				'backdrop_path.required' => 'O campo backdrop_path é obrigatório',
				'poster_path.required' => 'O campo poster_path é obrigatório',
				'release_date.required' => 'O campo release_date é obrigatório',
				'popularity.required' => 'O campo popularity é obrigatório',
				'vote_average.required' => 'O campo vote_average é obrigatório',
				'vote_count.required' => 'O campo vote_count é obrigatório',
				'genres.required' => 'O campo genres é obrigatório',
			]);

			$this->favoriteMovieService->add($request->all());

			return response()->json([
				'status' => 'success',
				'message' => 'Filme favorito adicionado com sucesso',
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
