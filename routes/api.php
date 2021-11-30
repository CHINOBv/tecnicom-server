<?php

use App\Http\Controllers\AuthController;
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
    $this->client_id = "359294529284647";
    $this->client_secret = "d43b1fce076bb4c522f70de3cb7a1ab5";
    $this->redirect_uri = "https://localhost:3000/";
    $this->scope = "user_profile,user_media";

    $this->ig_uri_access_token = 'https://api.instagram.com/oauth/access_token';
    $this->ig_uri_authorize = 'https://api.instagram.com/oauth/authorize?client_id='
        . $this->client_id .
        '&redirect_uri=' . $this->redirect_uri .
        '&scope=' . $this->scope .
        '&response_type=code';

    Route::get('authorize', function () {
        return redirect($this->redirect_uri);
    });

    Route::post('authorize-token', function (Request $request) {
        $code = $request->get('code');

        $data = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect_uri,
            'code' => $code
        );

        $res = Http::asForm()->post($this->ig_uri_access_token, $data);

        if (!$res->ok()) {
            return $res->getBody();
            response($res)->status(400);
        }

        return response()->json($res->body());
    });
});
