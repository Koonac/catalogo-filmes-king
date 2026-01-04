<?php

namespace Tests\Feature;

use App\Services\TmdbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TmdbControllerTest extends TestCase
{
	/**
	 * Testa a busca de filme com sucesso
	 */
	public function test_search_movie_success(): void
	{
		// Mock da resposta da API do TMDB
		$mockResponse = [
			'page' => 1,
			'results' => [
				[
					'id' => 550,
					'title' => 'Fight Club',
					'overview' => 'Um homem deprimido...',
					'release_date' => '1999-10-15',
				],
			],
			'total_pages' => 1,
			'total_results' => 1,
		];

		Http::fake([
			'api.themoviedb.org/3/search/movie*' => Http::response($mockResponse, 200),
		]);

		$response = $this->getJson('/api/tmdb/search-movie?movie=Fight Club');

		$response->assertStatus(200)
			->assertJsonStructure([
				'status',
				'data' => [
					'page',
					'results',
					'total_pages',
					'total_results',
				],
			])
			->assertJson([
				'status' => 'success',
			]);
	}

	/**
	 * Testa a busca de filme com parâmetros opcionais
	 */
	public function test_search_movie_with_optional_parameters(): void
	{
		$mockResponse = [
			'page' => 2,
			'results' => [],
			'total_pages' => 2,
			'total_results' => 0,
		];

		Http::fake([
			'api.themoviedb.org/3/search/movie*' => Http::response($mockResponse, 200),
		]);

		$response = $this->getJson('/api/tmdb/search-movie?movie=Test&page=2&language=en-US&include_adult=true&year=2020');

		$response->assertStatus(200)
			->assertJson([
				'status' => 'success',
			]);
	}

	/**
	 * Testa a busca de filme sem o parâmetro obrigatório
	 */
	public function test_search_movie_missing_required_parameter(): void
	{
		$response = $this->getJson('/api/tmdb/search-movie');

		$response->assertStatus(422)
			->assertJsonValidationErrors(['movie']);
	}

	/**
	 * Testa a busca de filme com parâmetro page inválido
	 */
	public function test_search_movie_invalid_page_parameter(): void
	{
		$response = $this->getJson('/api/tmdb/search-movie?movie=Test&page=invalid');

		$response->assertStatus(422)
			->assertJsonValidationErrors(['page']);
	}

	/**
	 * Testa a busca de filme quando a API do TMDB retorna erro
	 */
	public function test_search_movie_tmdb_api_error(): void
	{
		Http::fake([
			'api.themoviedb.org/3/search/movie*' => Http::response(['error' => 'Unauthorized'], 401),
		]);

		$response = $this->getJson('/api/tmdb/search-movie?movie=Test');

		$response->assertStatus(500)
			->assertJson([
				'status' => 'error',
				'message' => 'Erro ao buscar filme',
			]);
	}

	/**
	 * Testa a busca de filme quando há erro de conexão
	 */
	public function test_search_movie_connection_error(): void
	{
		Http::fake([
			'api.themoviedb.org/3/search/movie*' => function () {
				throw new \Exception('Connection timeout');
			},
		]);

		$response = $this->getJson('/api/tmdb/search-movie?movie=Test');

		$response->assertStatus(500)
			->assertJson([
				'status' => 'error',
				'message' => 'Erro ao buscar filme',
			]);
	}
}

