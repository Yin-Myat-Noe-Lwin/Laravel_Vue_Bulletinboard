<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Phone number or password is incorrect!'], 422);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24);

        return response()->json(['message' => 'Login Successfully!','user' => new UserResource($user), 'token' => $token])->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie()->forget('token');

        return response()->json(['message' => 'Logged out successfully!'])->withCookie($cookie);
    }

}
