<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class TmdbController extends Controller
{

	public function __construct(
		protected TmdbService $tmdbService,
	) {}

	/**
	 * Busca filmes na API do Tmdb por nome
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function searchMovie(Request $request)
	{
		try {
			$request->validate([
				'movie' => 'required|string|max:255',
				'page' => 'nullable|integer',
			], [
				'movie.required' => 'O campo movie é obrigatório',
				'page.integer' => 'O campo page deve ser um número inteiro',
			]);

			$tmdbResponse = $this->tmdbService->searchMovie($request->query());

			return response()->json([
				'status' => 'success',
				'data' => $tmdbResponse,
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Erro ao buscar filme',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Busca um filme na api por id
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getDetailsMovieById(Request $request)
	{
		try {
			$request->validate([
				'id' => 'required|integer'
			], [
				'id.required' => 'O campo id é obrigatório',
			]);

			$tmdbResponse = $this->tmdbService->getDetailsMovieById($request->query());

			return response()->json([
				'status' => 'success',
				'data' => $tmdbResponse,
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Erro ao buscar filme',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
