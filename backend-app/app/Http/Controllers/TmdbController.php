<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
		} catch (ValidationException $e) {
			throw $e;
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Erro ao buscar filme',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
