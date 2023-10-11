<?php

namespace App\Exports;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PostsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $loggedinUser = auth()->user();

        if (!$loggedinUser) {

            return Post::where('status', 1)->get();

        } else if (Auth::user()->isAdmin()) {

            return Post::withTrashed()->get();

        } else if (Auth::user()->isUser()) {

            return Post::where('create_user_id', $loggedinUser->id)->get();

        }
    }

    public function headings(): array
    {
        return ['id', 'title', 'description', 'status', 'create_user_id', 'updated_user_id', 'deleted_user_id', 'created_at', 'updated_at', 'deleted_at'];
    }
}
