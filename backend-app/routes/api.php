<?php

use App\Http\Controllers\FavoriteMovieController;
use App\Http\Controllers\TmdbController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return response()->json([
		'app' => config('app.name'),
		'version' => '1.0.0',
		'language' => app()->getLocale(),
		'timezone' => config('app.timezone'),
		'status' => 'online'
	]);
});

Route::group(['prefix' => 'tmdb'], function () {
	Route::get('/search-movie', [TmdbController::class, 'searchMovie']);
	Route::get('/details-movie', [TmdbController::class, 'getDetailsMovieById']);
});

Route::group(['prefix' => 'favorites'], function () {
	Route::get('/list', [FavoriteMovieController::class, 'list']);
	Route::post('/add-tmdb', [FavoriteMovieController::class, 'addByTmdbId']);
	Route::delete('/remove', [FavoriteMovieController::class, 'remove']);
});