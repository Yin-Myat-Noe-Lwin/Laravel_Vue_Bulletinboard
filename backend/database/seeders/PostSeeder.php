<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'title' => 'Test1',
            'description' => 'Test1 description',
            'status' => 1,
            'create_user_id' => 1,
            'updated_user_id' => 1
        ]);
        Post::create([
            'title' => 'Test2',
            'description' => 'Test2 description',
            'status' => 1,
            'create_user_id' => 2,
            'updated_user_id' => 2
        ]);
        Post::create([
            'title' => 'Test3',
            'description' => 'Test3 description',
            'status' => 1,
            'create_user_id' => 3,
            'updated_user_id' => 3
        ]);
        Post::create([
            'title' => 'Test4',
            'description' => 'Test4 description',
            'status' => 1,
            'create_user_id' => 4,
            'updated_user_id' => 4
        ]);
    }
}
