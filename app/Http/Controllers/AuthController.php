<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'rol_id' => 2,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {

        $credentials = request(['email', 'password']);

        $u = User::where('email', $credentials['email'])->first();

        if(!$u){
            return response()->json(['error' => 'Unauthorized', 'message' => 'Credenciales incorrectas'], 401);
        }

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Email o contraseña incorrectos'], 401);
        }

        return $this->respondWithToken($token, $credentials);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required',
            'name' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->getMessageBag()], 500);
        }

        $prevU = User::where('email', request('email'))->first();

        if($prevU)
        {
            return response()->json(['message' => 'Ya te has registerado con este email'], 401);
        }

        $this->create(request()->all());

        return $this->login();

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $credentials = null)
    {
        $res = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];

        if ($credentials !== null) {
            $user = User::where('email', '=', $credentials['email'])->get(['id', 'name', 'email']);
            $res['user'] = $user;
        }

        return response()->json($res);

    }
}
