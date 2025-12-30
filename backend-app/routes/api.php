<?php

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

Route::get('/test', function (Request $request) {
	return response()->json([
		'message' => 'Hello World'
	]);
});
