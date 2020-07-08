<?php

namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon;

class AuthController extends RespondController
{
    public function __construct() 
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'me']]);
    }

    protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        return User::create([
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
    }

    public function login(Request $request) 
    {
        $credentials = $request->only('email', 'password');
        if($token = $this->guard()->attempt($credentials, ['exp' => Carbon\Carbon::now()->addDays(3)->timestamp]))
        {
            return $this->respondWithToken($token);
        }
        return $this->sendResponse(false, "Login Failed", 401, [
            "error" => "Unauthorized"
        ]);
    }

    public function me()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired' => 400]);

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid' => 400]);

        } catch (JWTException $e) {

            return response()->json(['token_absent' => 400]);
        }
        return response()->json(compact('user'));
    }

    public function logout()
    {
        auth()->logout();

        return $this->sendResponse(true, "Logout success", 200, "");
    }

    protected function respondWithToken($token)
    {
        return $this->sendResponse(true, "login success", 200, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'bearer',
        //     'expires_in' => $this->guard()->factory()->getTTL() * 60
        // ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}
