<?php

use Illuminate\Support\Facades\Route;

Route::any('{any}', function () {
    return response()->json([
        'message' => 'API Endpoint - Para documentação acesse /api',
        'status' => 'success'
    ], 200);
})->where('any', '.*');