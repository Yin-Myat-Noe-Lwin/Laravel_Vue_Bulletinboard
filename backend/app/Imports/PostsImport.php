<?php

namespace App\Imports;

use App\Models\Post;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

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
            'title' => ['required', 'string' , 'max:255' ,Rule::unique('posts', 'title')->whereNull('deleted_at') ],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in([0, 1])]
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
