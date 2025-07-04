<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('articles', [ArticleController::class, 'index']);
Route::get('categories', [ArticleController::class, 'getCategories']);
Route::get('sources', [ArticleController::class, 'getSources']);
Route::get('authors', [ArticleController::class, 'getAuthors']);

Route::middleware('auth:api')->group(function () {
  Route::post('logout', [AuthController::class, 'logout']);
  Route::get('me', [AuthController::class, 'me']);
  Route::get('preferences', [ArticleController::class, 'getPreferences']);
  Route::post('preferences', [ArticleController::class, 'updatePreferences']);
});
