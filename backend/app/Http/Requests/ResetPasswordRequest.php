<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'password' => ['required', 'string', Password::min(8), 'regex:/^(?=.*[A-Z])(?=.*[!@#\$%\^&\*]).+$/', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Password can\'t be blank.',
            'password.confirmed' => 'Password and Password confirmation do not match.',
            'password_confirmation.required' => 'Password confirmation can\'t be blank.'
        ];
    }
}
