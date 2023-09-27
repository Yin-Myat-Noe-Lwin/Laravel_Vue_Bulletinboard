<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Mail\PasswordResetMail;
use App\Models\PasswordResetToken;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class PasswordController extends Controller
{

    public function forgotPassword(ForgotPasswordRequest $request) {

        $user = User::where('email', $request->email)->first();
        $userName = $user->name;
        $userId = $user->id;

        if (!$user) {
            return response()->json(['error' => 'Email does not exist!'], 422);
        }

        $token = Str::random(64);

        $passwordReset = PasswordReset::where('email', $request->email)->first();

        if ($passwordReset) {
            $passwordReset->update([
                'token' => $token,
            ]);
        } else {
            $passwordReset = new PasswordReset();
            $passwordReset->email = $request->email;
            $passwordReset->token = $token;
            $passwordReset->save();
        }

        $mailData = [
            'token' => $token,
            'email' => $request->email,
            'userName' => $userName,
            'userId' => $userId
        ];

        info($mailData);

        Mail::to($request->email)->send(new PasswordResetMail($mailData));

        return response()->json(['message' => 'Password reset email sent successfully!'], 200);

    }


    public function resetPassword(ResetPasswordRequest $request){

        $user = User::where('id', $request->userId)->first();
        $userEmail = $user->email;

        if (!$user) {
            return response()->json(['error' => 'User not found'], 422);
        }

        $passwordReset = PasswordReset::where('email', $userEmail )
                                        ->where('token', $request->token)
                                        ->first();

        $user->password = Hash::make($request->password);

        $user->save();

        $passwordReset->delete();

        return response()->json(['message' => 'Password reset successfully!'], 200);

    }

}
