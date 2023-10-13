<?php

namespace App\Imports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Post;

class PostsImport implements ToCollection,  WithValidation,  WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function rules(): array
    {
        return [
            'title' => ['required', 'max:255' ,Rule::unique('posts', 'title')->whereNull('deleted_at') ],
            'description' => ['required'],
            'status' => ['required', Rule::in([0, 1])]
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

    public function collection(Collection $rows)
    {
        $posts = [];

        foreach ($rows as $row) {

            $post = new Post();

            $post->title = $row['title'];

            $post->description = $row['description'];

            $post->status = $row['status'];

            $post->create_user_id = Auth::id() ;

            $post->updated_user_id = Auth::id();

            $post->save();

            $posts[] = $post;
        }

        return $posts;
    }

}
