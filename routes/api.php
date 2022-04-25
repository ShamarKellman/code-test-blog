<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')
    ->name('v1.')
    ->group(function () {
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

        Route::get('/posts', [PostController::class, 'index'])->name('post.index');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('post.show');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/posts', [PostController::class, 'store'])->name('post.store');
            Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('post.destroy');
            Route::post('/posts/{post}/like', [LikeController::class, 'update'])->name('like.update');
            Route::post('/posts/{post}/unlike', [LikeController::class, 'destroy'])->name('like.destroy');
        });
    });
