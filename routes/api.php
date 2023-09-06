<?php

use App\Http\Controllers\api\BookController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\GenreController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/greet', 'UserController@greet');

Route::group(function () {

    Route::post('/error/403', [AuthController::class, 'badrequest'])->name('badrequest');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware('auth:sanctum');

    Route::get('/genres', [GenreController::class, 'index']);
    Route::get('/genres/{id}', [GenreController::class, 'show']);
    Route::post('/genres', [GenreController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/genres/{id}', [GenreController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/genres/{id}', [GenreController::class, 'destroy'])->middleware('auth:sanctum');

    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::post('/books', [BookController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/books/{id}', [BookController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->middleware('auth:sanctum');
})->withoutMiddleware("throttle:api")
    ->middleware("throttle:1000:1");
