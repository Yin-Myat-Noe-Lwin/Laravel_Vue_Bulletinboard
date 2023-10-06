<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
        $postId = $this->route('post')->id;

        return [
            'title' => ['required', 'string', 'max:255' ,Rule::unique('posts', 'title')->whereNull('deleted_at')->ignore($postId)],
            'description' => ['required'],
            'status' => ['required', 'in:0,1'],
            'create_user_id' => 'required',
            'updated_user_id' => 'required'
        ];
    }
}
