<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstagramAuthControllerApi;
use App\Http\Controllers\InstagramControllerApi;
use App\Http\Controllers\UnsplashControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

Route::prefix('auth')->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::prefix('ig')->group(function () {
    Route::get('authorize', [InstagramAuthControllerApi::class, 'authorizeCode']);
    Route::post('authorize-token', [InstagramAuthControllerApi::class, 'authorizeToken']);

    Route::resource('media', InstagramControllerApi::class);
});

Route::prefix('unsplash')->group(function () {
    Route::get('/', [UnsplashControllerApi::class, 'index']);
});
