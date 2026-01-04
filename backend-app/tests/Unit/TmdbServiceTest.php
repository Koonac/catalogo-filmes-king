<?php

namespace Tests\Unit;

use App\Services\TmdbService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TmdbServiceTest extends TestCase
{
	protected TmdbService $tmdbService;

	protected function setUp(): void
	{
		parent::setUp();
		$this->tmdbService = new TmdbService();
	}

	/**
	 * Testa a busca de filme com sucesso
	 */
	public function test_search_movie_success(): void
	{
		$mockResponse = [
			'page' => 1,
			'results' => [
				[
					'id' => 550,
					'title' => 'Fight Club',
					'overview' => 'Um homem deprimido...',
				],
			],
			'total_pages' => 1,
			'total_results' => 1,
		];

		Http::fake([
			'api.themoviedb.org/3/search/movie*' => Http::response($mockResponse, 200),
		]);

		$result = $this->tmdbService->searchMovie([
			'movie' => 'Fight Club',
			'page' => 1,
		]);

		$this->assertEquals($mockResponse, $result);
		$this->assertEquals(1, $result['page']);
		$this->assertCount(1, $result['results']);
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

		$result = $this->tmdbService->searchMovie([
			'movie' => 'Test',
			'page' => 2,
			'language' => 'en-US',
			'include_adult' => true,
			'year' => 2020,
		]);

		$this->assertEquals($mockResponse, $result);
	}

	/**
	 * Testa a busca de filme com valores padrão
	 */
	public function test_search_movie_with_default_values(): void
	{
		$mockResponse = [
			'page' => 1,
			'results' => [],
			'total_pages' => 1,
			'total_results' => 0,
		];

		Http::fake([
			'api.themoviedb.org/3/search/movie*' => Http::response($mockResponse, 200),
		]);

		$result = $this->tmdbService->searchMovie([
			'movie' => 'Test',
		]);

		$this->assertEquals($mockResponse, $result);
	}

	/**
	 * Testa a busca de filme quando a API retorna erro
	 */
	public function test_search_movie_api_error(): void
	{
		Http::fake([
			'api.themoviedb.org/3/search/movie*' => Http::response(['error' => 'Unauthorized'], 401),
		]);

		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Erro ao buscar filme na API');

		$this->tmdbService->searchMovie([
			'movie' => 'Test',
		]);
	}

	/**
	 * Testa a busca de detalhes de filme por ID com sucesso
	 */
	public function test_get_details_movie_by_id_success(): void
	{
		$movieId = 550;
		$mockResponse = [
			'id' => $movieId,
			'title' => 'Fight Club',
			'overview' => 'Um homem deprimido...',
			'release_date' => '1999-10-15',
			'genres' => [['id' => 18, 'name' => 'Drama']],
		];

		Http::fake([
			'api.themoviedb.org/3/movie/' . $movieId . '*' => Http::response($mockResponse, 200),
		]);

		$result = $this->tmdbService->getDetailsMovieById([
			'id' => $movieId,
		]);

		$this->assertEquals($mockResponse, $result);
		$this->assertEquals($movieId, $result['id']);
	}

	/**
	 * Testa a busca de detalhes de filme por ID com parâmetros opcionais
	 */
	public function test_get_details_movie_by_id_with_optional_parameters(): void
	{
		$movieId = 550;
		$mockResponse = [
			'id' => $movieId,
			'title' => 'Fight Club',
		];

		Http::fake([
			'api.themoviedb.org/3/movie/' . $movieId . '*' => Http::response($mockResponse, 200),
		]);

		$result = $this->tmdbService->getDetailsMovieById([
			'id' => $movieId,
			'language' => 'en-US',
			'append_to_response' => 'credits,videos',
		]);

		$this->assertEquals($mockResponse, $result);
	}

	/**
	 * Testa a busca de detalhes de filme por ID quando a API retorna erro
	 */
	public function test_get_details_movie_by_id_api_error(): void
	{
		$movieId = 999999;

		Http::fake([
			'api.themoviedb.org/3/movie/' . $movieId . '*' => Http::response(['error' => 'Not Found'], 404),
		]);

		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Erro ao buscar filme na API');

		$this->tmdbService->getDetailsMovieById([
			'id' => $movieId,
		]);
	}

	/**
	 * Testa a busca de detalhes de filme por ID quando há erro de conexão
	 */
	public function test_get_details_movie_by_id_connection_error(): void
	{
		$movieId = 550;

		Http::fake([
			'api.themoviedb.org/3/movie/' . $movieId . '*' => function () {
				throw new \Exception('Connection timeout');
			},
		]);

		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Erro ao buscar filme na API');

		$this->tmdbService->getDetailsMovieById([
			'id' => $movieId,
		]);
	}
}

