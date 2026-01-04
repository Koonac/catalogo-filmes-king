<?php

namespace Database\Factories;

use App\Models\FavoriteMovie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FavoriteMovie>
 */
class FavoriteMovieFactory extends Factory
{
	protected $model = FavoriteMovie::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'tmdb_id' => (string) fake()->unique()->numberBetween(1, 100000),
			'adult' => false,
			'original_language' => 'en',
			'original_title' => fake()->sentence(3),
			'title' => fake()->sentence(3),
			'overview' => fake()->paragraph(),
			'backdrop_path' => '/backdrop.jpg',
			'poster_path' => '/poster.jpg',
			'release_date' => fake()->date('Y-m-d'),
			'popularity' => fake()->randomFloat(2, 0, 100),
			'vote_average' => fake()->randomFloat(1, 0, 10),
			'vote_count' => fake()->numberBetween(0, 10000),
			'genres' => [
				['id' => fake()->numberBetween(1, 30), 'name' => fake()->word()],
			],
		];
	}
}

