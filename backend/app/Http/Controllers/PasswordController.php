<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\ChangePasswordRequest;

class PasswordController extends Controller
{

    public function changePassword(ChangePasswordRequest $request, User $user) {

        info("hey ");

        $user->update($request->all());

        return response()->json(['message' => 'Password updated successfully','user' =>  new UserResource($user)]);
    }

    public function forgotPassword() {

    }

    public function resetPassword() {

    }

}
