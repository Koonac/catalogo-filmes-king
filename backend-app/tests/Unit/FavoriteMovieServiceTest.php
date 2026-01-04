<?php

namespace Tests\Unit;

use App\Models\FavoriteMovie;
use App\Services\FavoriteMovieService;
use App\Services\TmdbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FavoriteMovieServiceTest extends TestCase
{
	use RefreshDatabase;

	protected FavoriteMovieService $favoriteMovieService;

	protected function setUp(): void
	{
		parent::setUp();
		$this->favoriteMovieService = new FavoriteMovieService(new FavoriteMovie());
	}

	/**
	 * Testa a listagem de filmes favoritos com sucesso
	 */
	public function test_list_success(): void
	{
		FavoriteMovie::factory()->count(10)->create();

		$result = $this->favoriteMovieService->list(15, []);

		$this->assertCount(10, $result->items());
		$this->assertEquals(10, $result->total());
	}

	/**
	 * Testa a listagem de filmes favoritos com paginação
	 */
	public function test_list_with_pagination(): void
	{
		FavoriteMovie::factory()->count(20)->create();

		$result = $this->favoriteMovieService->list(10, []);

		$this->assertCount(10, $result->items());
		$this->assertEquals(20, $result->total());
		$this->assertEquals(10, $result->perPage());
	}

	/**
	 * Testa a listagem de filmes favoritos com filtro de busca
	 */
	public function test_list_with_search_filter(): void
	{
		FavoriteMovie::factory()->create(['title' => 'Matrix']);
		FavoriteMovie::factory()->create(['title' => 'Inception']);
		FavoriteMovie::factory()->create(['title' => 'Interstellar']);

		$result = $this->favoriteMovieService->list(15, ['search' => 'Matrix']);

		$this->assertCount(1, $result->items());
		$this->assertEquals('Matrix', $result->items()[0]->title);
	}

	/**
	 * Testa a adição de filme favorito por tmdb_id com sucesso
	 */
	public function test_add_by_tmdb_id_success(): void
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

		$result = $this->favoriteMovieService->addByTmdbId($tmdbId);

		$this->assertInstanceOf(FavoriteMovie::class, $result);
		$this->assertEquals($tmdbId, $result->tmdb_id);
		$this->assertEquals('Clube da Luta', $result->title);
		$this->assertDatabaseHas('favorites_movies', [
			'tmdb_id' => $tmdbId,
			'title' => 'Clube da Luta',
		]);
	}

	/**
	 * Testa a adição de filme favorito que já existe
	 */
	public function test_add_by_tmdb_id_already_exists(): void
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
		$this->favoriteMovieService->addByTmdbId($tmdbId);

		// Tenta adicionar novamente
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Filme já favoritado');

		$this->favoriteMovieService->addByTmdbId($tmdbId);
	}

	/**
	 * Testa a adição de filme favorito quando o filme não existe no TMDB
	 */
	public function test_add_by_tmdb_id_movie_not_found(): void
	{
		$tmdbId = 999999;

		Http::fake([
			'api.themoviedb.org/3/movie/' . $tmdbId . '*' => Http::response(['error' => 'Not Found'], 404),
		]);

		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Erro ao adicionar filme favorito');

		$this->favoriteMovieService->addByTmdbId($tmdbId);
	}

	/**
	 * Testa a adição de filme favorito diretamente
	 */
	public function test_add_success(): void
	{
		$movieData = [
			'tmdb_id' => 550,
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

		$result = $this->favoriteMovieService->add($movieData);

		$this->assertInstanceOf(FavoriteMovie::class, $result);
		$this->assertEquals(550, $result->tmdb_id);
		$this->assertEquals('Clube da Luta', $result->title);
		$this->assertDatabaseHas('favorites_movies', [
			'tmdb_id' => 550,
			'title' => 'Clube da Luta',
		]);
	}

	/**
	 * Testa a remoção de filme favorito com sucesso
	 */
	public function test_remove_success(): void
	{
		$favoriteMovie = FavoriteMovie::factory()->create();

		$result = $this->favoriteMovieService->remove($favoriteMovie->id);

		$this->assertTrue($result);
		$this->assertDatabaseMissing('favorites_movies', [
			'id' => $favoriteMovie->id,
		]);
	}

	/**
	 * Testa a remoção de filme favorito que não existe
	 */
	public function test_remove_not_found(): void
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Filme favorito não encontrado');

		$this->favoriteMovieService->remove(99999);
	}
}
