<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{

	/**
	 * Busca filmes na API do Tmdb por nome
	 * @param array $parametros
	 * @return array
	 */
	public function searchMovie($parametros)
	{
		try {
			$movie = $parametros['movie'];
			$page = $parametros['page'] ?? 1;
			$includeAdult = $parametros['include_adult'] ?? false;
			$language = $parametros['language'] ?? 'pt-BR';
			$year = $parametros['year'] ?? null;

			$response = Http::withHeaders([
				'Authorization' => 'Bearer ' . env('TMDB_TOKEN')
			])->get('https://api.themoviedb.org/3/search/movie', [
				'query' => $movie,
				'page' => $page,
				'include_adult' => $includeAdult,
				'language' => $language,
				'year' => $year,
			]);

			if ($response->successful()) {
				return $response->json();
			} else {
				throw new \Exception('Erro ao buscar filme na API: ' . $response->body());
			}
		} catch (\Exception $e) {
			throw new \Exception('Erro ao buscar filme na API: ' . $e->getMessage());
		}
	}

	/**
	 * Busca um filme na api por id
	 * @param array $parametros
	 * @return array
	 */
	public function getDetailsMovieById($parametros)
	{
		try {
			$id = $parametros['id'];
			$language = $parametros['language'] ?? 'pt-BR';
			$appendToResponse = $parametros['append_to_response'] ?? null;

			$response = Http::withHeaders([
				'Authorization' => 'Bearer ' . env('TMDB_TOKEN')
			])->get('https://api.themoviedb.org/3/movie/' . $id, [
				'language' => $language,
				'append_to_response' => $appendToResponse,
			]);

			if ($response->successful()) {
				return $response->json();
			} else {
				throw new \Exception('Erro ao buscar filme na API: ' . $response->body());
			}
		} catch (\Exception $e) {
			throw new \Exception('Erro ao buscar filme na API: ' . $e->getMessage());
		}
	}
}
