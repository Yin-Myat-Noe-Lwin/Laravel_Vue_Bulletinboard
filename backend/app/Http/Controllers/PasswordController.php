<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class PasswordController extends Controller
{

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {

            return response()->json(['error' => 'Email does not exist!'], 422);

        } else {

            $userName = $user->name;
            $userId = $user->id;

            $token = Str::random(64);

            $passwordReset = PasswordReset::where('email', $request->email)->first();

            if ($passwordReset) {

                //if user email has already password reset token, update it with new token
                $passwordReset->update([
                    'token' => $token,
                ]);

            } else {

                //if not, create new password reset token
                $passwordReset = new PasswordReset();
                $passwordReset->email = $request->email;
                $passwordReset->token = $token;
                $passwordReset->save();

            }

            //create mail data required for password reset
            $mailData = [
                'token' => $token,
                'email' => $request->email,
                'userName' => $userName,
                'userId' => $userId
            ];

            //send mail
            Mail::to($request->email)->send(new PasswordResetMail($mailData));

            return response()->json(['message' => 'Email sent with password reset instructions.'], 200);

        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        //find user with id
        $user = User::where('id', $request->userId)->first();

        $userEmail = $user->email;

        if (!$user) {

            return response()->json(['error' => 'User not found'], 422);

        }

        $passwordReset = PasswordReset::where('email', $userEmail )
                                        ->where('token', $request->token)
                                        ->first();

        //change user password
        $user->password = Hash::make($request->password);

        $user->save();

        //delete password reset token
        $passwordReset->delete();

        return response()->json(['message' => 'Password has been reset.'], 200);
    }

}
