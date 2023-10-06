<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Validated;
use Laravel\Passport\Passport;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {

            $user = User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'phoneNumber' => $request->input('phoneNumber'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            $token = $user->createToken('user_token')->accessToken;

            return response()->json([ 'user' => $user, 'token' => $token ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in AuthController.register'
            ]);
        }
    }
    public function login(LoginRequest $request)
    {

        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
             $token = $user->createToken('user_token')->accessToken;
            return response()->json([ 'user' => $user, 'token' => $token ], 200);

        }
        return response()->json(['message' => 'Failed to Authenticate'], 401);
    }
}
