<?php

namespace App\Http\Requests;

use App\Rules\MatchOldPassword;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'old_password'=> ['required', new MatchOldPassword],
            'password' => ['required', 'string', Password::min(8), 'regex:/^(?=.*[A-Z])(?=.*[!@#\$%\^&\*]).+$/' , 'confirmed'],
            'password_confirmation' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required' => 'Current password can\'t be blank.',
            'password.required' => 'New password can\'t be blank.',
            'password.regex' => 'Password must contain at least one Uppercase letter and one special character.',
            'password.confirmed' => 'New password and New confirm password confirmation is not match.',
            'password_confirmation.required' => 'New confirm password can\'t be blank.'
        ];
    }
}
