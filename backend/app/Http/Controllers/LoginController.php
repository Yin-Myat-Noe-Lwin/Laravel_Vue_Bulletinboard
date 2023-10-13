<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            //check if entered email address exits in database
            return response()->json(['error' => 'Email does not exist!'], 422);
        }

        if (!Hash::check($request->password, $user->password)) {
            //check if entered password is correct or not
            return response()->json(['error' => 'Password is incorrect!'], 422);
        }

        //create login token
        $token = $user->createToken('auth_token')->plainTextToken;

        //token expiry date
        $cookie = cookie('token', $token, 60 * 24 * 30 );

        return response()->json([
            'message' => 'Login Successfully!',
            'user' => new UserResource($user),
            'token' => $token
        ])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        //delete token
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie()->forget('token');

        return response()->json(['message' => 'Logged out successfully!'])->withCookie($cookie);
    }

}
