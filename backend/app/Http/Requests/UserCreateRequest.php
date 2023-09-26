<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required','string',Password::min(5),'confirmed' ],
            'password_confirmation' => ['required'],
            'profile' => ['required', 'image', 'mimes:jpeg,png,jpg,jfif' , 'max:2048'],
            'type' => ['required', 'in:0,1'],
            'phone' => ['nullable', 'regex:/^(09-|01-|\+?959-)\d{9}$/'],
            'address' => ['nullable','string', 'max:255'],
            'dob' => ['nullable', 'string'],
            'create_user_id' => 'required',
            'updated_user_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'profile.max' => 'The uploaded image must be less than 2 MB in size.',
        ];
    }
}
