<?php

    namespace App\Http\Requests;

    use Illuminate\Validation\Rule;
    use Illuminate\Foundation\Http\FormRequest;

    class PostCreateRequest extends FormRequest
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
                'title' => ['required', 'max:255' , Rule::unique('posts', 'title')->whereNull('deleted_at') ],
                'description' => ['required', 'max:255'],
                'status' => ['required', 'in:0,1'],
                'create_user_id' => 'required',
                'updated_user_id' => 'required'
            ];
        }

        public function messages()
        {
            return [
                'title.required' => 'Title can\'t be blank.',
                'title.max' => '255 characters is the maximum allowed.',
                'description.required' => 'Description can\'t be blank.'
            ];
        }
    }
