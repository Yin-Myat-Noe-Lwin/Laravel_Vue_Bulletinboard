<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:40', Rule::unique('users', 'name')->whereNull('deleted_at')->ignore($userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($userId)],
            'password' => ['required','string',Password::min(5),'confirmed' ],
            'profile' => 'nullable|mimes:jpeg,png,jpg,jfif',
            'type' => ['required', 'in:0,1'],
            'phone' => ['nullable', 'regex:/^(09-|01-|\+?959-)\d{9}$/'],
            'address' => ['nullable','string', 'max:255'],
            'dob' => ['nullable', 'string'],
            'create_user_id' => 'required',
            'updated_user_id' => 'required'
        ];
    }
}
