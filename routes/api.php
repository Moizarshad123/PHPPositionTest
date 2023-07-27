<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FilmController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::POST('logout', [AuthController::class, 'logout']);
    Route::GET('fetch-movies', [FilmController::class, 'fetch_movies']);
    Route::GET('movies', [FilmController::class, 'movies']);
    Route::PUT('modify-movie', [FilmController::class, 'modify_movie']);
    Route::DELETE('delete-movie', [FilmController::class, 'delete_movie']);
    Route::GET('search-movie', [FilmController::class, 'search_movie']);

});
Route::prefix('auth')->group(function() {

    Route::post('login', [AuthController::class, 'login']);
    Route::get('unauthenticated', [AuthController::class, 'unauthenticatedUser'])->name('api.unauthenticated');
});