<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:40', Rule::unique('users', 'name')->whereNull('deleted_at') ],
            'email' => ['required', 'email', 'max:40', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required','string', Password::min(8), 'regex:/^(?=.*[A-Z])(?=.*[!@#\$%\^&\*]).+$/' ,'confirmed' ],
            'password_confirmation' => ['required'],
            'profile' => ['required', 'image', 'mimes:jpeg,png,jpg,jfif' , 'max:2048'],
            'type' => ['required', 'string', 'in:0,1'],
            'phone' => ['nullable', 'string', 'regex:/^(09-|01-|\+?959-)\d{9}$/'],
            'address' => ['required','string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'create_user_id' => 'nullable',
            'updated_user_id' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name can\'t be blank.',
            'email.required' => 'Email can\'t be blank.',
            'email.email' => 'Email format is invalid.',
            'password.required' => 'Password can\'t be blank.',
            'password.regex' => 'Password must contain at least one Uppercase letter and one special character.',
            'password.confirmed' => 'Password and Password confirmation do not match.',
            'password_confirmation.required' => 'Password can\'t be blank.',
            'address.required' => 'Address can\'t be blank.',
            'profile.required' => 'Profile can\'t be blank.',
            'profile.max' => 'The uploaded image must be less than 2 MB in size.',
        ];
    }
}
