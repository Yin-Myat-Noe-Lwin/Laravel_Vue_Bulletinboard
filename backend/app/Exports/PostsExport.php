<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Post;

class PostsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //get logged in user
        $loggedinUser = Auth::user();

        if (!$loggedinUser) {

            //if not logged in, export all posts except with inactive status
            return Post::where('status', 1)->get();

        } else if ($loggedinUser->isAdmin()) {

            //if admin, export all posts
            return Post::withTrashed()->get();

        } else if ($loggedinUser->isUser()) {

            //if user, export posts created by logged in user
            return Post::where('create_user_id', $loggedinUser->id)->get();

        }
    }

    public function headings(): array
    {
        return ['id', 'title', 'description', 'status', 'create_user_id', 'updated_user_id', 'deleted_user_id', 'created_at', 'updated_at', 'deleted_at'];
    }
}
