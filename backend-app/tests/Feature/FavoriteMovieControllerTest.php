<?php

namespace Tests\Feature;

use App\Models\FavoriteMovie;
use App\Services\TmdbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FavoriteMovieControllerTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * Testa a listagem de filmes favoritos com sucesso
	 */
	public function test_list_favorites_success(): void
	{
		// Criando filmes favoritos de teste
		FavoriteMovie::factory()->count(5)->create();

		$response = $this->getJson('/api/favorites/list');

		$response->assertStatus(200)
			->assertJsonStructure([
				'status',
				'data',
				'pagination' => [
					'current_page',
					'per_page',
					'total',
					'last_page',
					'from',
					'to',
				],
			])
			->assertJson([
				'status' => 'success',
			]);

		$this->assertCount(5, $response->json('data'));
	}

	/**
	 * Testa a listagem de filmes favoritos com paginação
	 */
	public function test_list_favorites_with_pagination(): void
	{
		FavoriteMovie::factory()->count(20)->create();

		$response = $this->getJson('/api/favorites/list?per_page=10&page=1');

		$response->assertStatus(200)
			->assertJson([
				'status' => 'success',
				'pagination' => [
					'per_page' => 10,
					'current_page' => 1,
				],
			]);

		$this->assertCount(10, $response->json('data'));
	}

	/**
	 * Testa a listagem de filmes favoritos com filtro de busca
	 */
	public function test_list_favorites_with_search_filter(): void
	{
		FavoriteMovie::factory()->create(['title' => 'Matrix']);
		FavoriteMovie::factory()->create(['title' => 'Inception']);
		FavoriteMovie::factory()->create(['title' => 'Interstellar']);

		$response = $this->getJson('/api/favorites/list?search=Matrix');

		$response->assertStatus(200)
			->assertJson([
				'status' => 'success',
			]);

		$data = $response->json('data');
		$this->assertCount(1, $data);
		$this->assertEquals('Matrix', $data[0]['title']);
	}

	/**
	 * Testa a listagem de filmes favoritos com parâmetros inválidos
	 */
	public function test_list_favorites_invalid_parameters(): void
	{
		$response = $this->getJson('/api/favorites/list?per_page=invalid&page=invalid');

		$response->assertStatus(422)
			->assertJsonValidationErrors(['per_page', 'page']);
	}

	/**
	 * Testa a adição de filme favorito com sucesso
	 */
	public function test_add_favorite_success(): void
	{
		$tmdbId = 550;
		$mockTmdbResponse = [
			'id' => $tmdbId,
			'adult' => false,
			'original_language' => 'en',
			'original_title' => 'Fight Club',
			'title' => 'Clube da Luta',
			'overview' => 'Um homem deprimido...',
			'backdrop_path' => '/backdrop.jpg',
			'poster_path' => '/poster.jpg',
			'release_date' => '1999-10-15',
			'popularity' => 50.5,
			'vote_average' => 8.4,
			'vote_count' => 1000,
			'genres' => [['id' => 18, 'name' => 'Drama']],
		];

		Http::fake([
			'api.themoviedb.org/3/movie/' . $tmdbId . '*' => Http::response($mockTmdbResponse, 200),
		]);

		$response = $this->postJson('/api/favorites/add-tmdb', [
			'tmdb_id' => $tmdbId,
		]);

		$response->assertStatus(200)
			->assertJsonStructure([
				'status',
				'message',
				'data',
			])
			->assertJson([
				'status' => 'success',
				'message' => 'Filme favorito adicionado com sucesso',
			]);

		$this->assertDatabaseHas('favorites_movies', [
			'tmdb_id' => $tmdbId,
			'title' => 'Clube da Luta',
		]);
	}

	/**
	 * Testa a adição de filme favorito sem o parâmetro obrigatório
	 */
	public function test_add_favorite_missing_required_parameter(): void
	{
		$response = $this->postJson('/api/favorites/add-tmdb', []);

		$response->assertStatus(422)
			->assertJsonValidationErrors(['tmdb_id']);
	}

	/**
	 * Testa a adição de filme favorito que já existe
	 */
	public function test_add_favorite_already_exists(): void
	{
		$tmdbId = 550;
		$mockTmdbResponse = [
			'id' => $tmdbId,
			'adult' => false,
			'original_language' => 'en',
			'original_title' => 'Fight Club',
			'title' => 'Clube da Luta',
			'overview' => 'Um homem deprimido...',
			'backdrop_path' => '/backdrop.jpg',
			'poster_path' => '/poster.jpg',
			'release_date' => '1999-10-15',
			'popularity' => 50.5,
			'vote_average' => 8.4,
			'vote_count' => 1000,
			'genres' => [['id' => 18, 'name' => 'Drama']],
		];

		Http::fake([
			'api.themoviedb.org/3/movie/' . $tmdbId . '*' => Http::response($mockTmdbResponse, 200),
		]);

		// Adiciona o filme pela primeira vez
		$this->postJson('/api/favorites/add-tmdb', ['tmdb_id' => $tmdbId]);

		// Tenta adicionar novamente
		$response = $this->postJson('/api/favorites/add-tmdb', ['tmdb_id' => $tmdbId]);

		$response->assertStatus(500)
			->assertJson([
				'status' => 'error',
				'message' => 'Erro ao adicionar filme favorito',
			]);
	}

	/**
	 * Testa a adição de filme favorito quando o filme não existe no TMDB
	 */
	public function test_add_favorite_movie_not_found_in_tmdb(): void
	{
		$tmdbId = 999999;

		Http::fake([
			'api.themoviedb.org/3/movie/' . $tmdbId . '*' => Http::response(['error' => 'Not Found'], 404),
		]);

		$response = $this->postJson('/api/favorites/add-tmdb', [
			'tmdb_id' => $tmdbId,
		]);

		$response->assertStatus(500)
			->assertJson([
				'status' => 'error',
				'message' => 'Erro ao adicionar filme favorito',
			]);
	}

	/**
	 * Testa a remoção de filme favorito com sucesso
	 */
	public function test_remove_favorite_success(): void
	{
		$favoriteMovie = FavoriteMovie::factory()->create();

		$response = $this->deleteJson('/api/favorites/remove', [
			'id' => $favoriteMovie->id,
		]);

		$response->assertStatus(200)
			->assertJson([
				'status' => 'success',
				'message' => 'Filme favorito removido com sucesso',
			]);

		$this->assertDatabaseMissing('favorites_movies', [
			'id' => $favoriteMovie->id,
		]);
	}

	/**
	 * Testa a remoção de filme favorito sem o parâmetro obrigatório
	 */
	public function test_remove_favorite_missing_required_parameter(): void
	{
		$response = $this->deleteJson('/api/favorites/remove', []);

		$response->assertStatus(422)
			->assertJsonValidationErrors(['id']);
	}

	/**
	 * Testa a remoção de filme favorito que não existe
	 */
	public function test_remove_favorite_not_found(): void
	{
		$response = $this->deleteJson('/api/favorites/remove', [
			'id' => 99999,
		]);

		$response->assertStatus(500)
			->assertJson([
				'status' => 'error',
				'message' => 'Erro ao remover filme favorito',
			]);
	}
}
