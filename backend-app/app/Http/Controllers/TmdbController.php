<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class TmdbController extends Controller
{

	public function __construct(
		protected TmdbService $tmdbService,
	) {}

	public function searchMovie(Request $request)
	{
		try {
			$request->validate([
				'movie' => 'required|string|max:255'
			], [
				'movie.required' => 'O campo movie é obrigatório',
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
}
