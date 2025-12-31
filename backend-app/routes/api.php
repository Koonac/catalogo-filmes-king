<?php

use App\Http\Controllers\TmdbController;
use Illuminate\Http\Request;
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

/*
 * Tmdb API - Search Movie
 */
Route::get('/search-movie', [TmdbController::class, 'searchMovie']);
