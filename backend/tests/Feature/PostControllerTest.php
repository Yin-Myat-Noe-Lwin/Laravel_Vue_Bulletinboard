<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class PostControllerTest extends TestCase
{
    use DatabaseTruncation;

    // public function test_index_post(): void
    // {
    //     Post::factory()->count(5)->create();

    //     $response = $this->get(route('posts.index'));

    //     $response->assertStatus(200);
    // }

    public function test_create_post_with_authenication(): void
    {
        $user = User::factory()->create();

        //current logged in user
        $this->actingAs($user);

        $postData = [
            'title' => 'Sample Title',
            'description' => 'This is a sample description.',
            'status' => 1,
            'create_user_id' => $user->id,
            'updated_user_id' => $user->id
        ];

        $response = $this->post(route('posts.store'), $postData);

        $response->assertStatus(200);
    }

    public function test_create_post_with_invalid_data(): void
    {
        $user = User::factory()->create();

        //current logged in user
        $this->actingAs($user);

        $postData = [
            'title' => '',
            'description' => '',
            'status' => 1,
            'create_user_id' => $user->id,
            'updated_user_id' =>  $user->id,
        ];

        $response = $this->post(route('posts.store'), $postData);

        $response->assertStatus(302);
    }

    public function test_create_post_without_authenication(): void
    {
        $postData = [
            'title' => 'Sample Title',
            'description' => 'This is a sample description.',
            'status' => 1,
            'create_user_id' => 1,
            'updated_user_id' => 1
        ];

        $response = $this->post(route('posts.store'), $postData);

        $response->assertStatus(302);
    }

    // public function test_show_post(): void
    // {
    //     $post = Post::factory()->create();

    //     $response = $this->get(route('posts.show', ['post' => $post->id]));

    //     $response->assertStatus(200);
    // }

    // public function test_update_post(): void
    // {
    //     $post = Post::factory()->create();

    //     $updatedPostData = [
    //         'title' => 'Updated Title',
    //         'description' => 'Updated Description.',
    //         'status' => 1,
    //         'create_user_id' => 1,
    //         'updated_user_id' => 1
    //     ];

    //     $response = $this->put(route('posts.update', ['post' => $post->id]), $updatedPostData);

    //     $response->assertStatus(200);
    // }

    public function test_delete_post_with_authenication(): void
    {
        $user = User::factory()->create();

        //current logged in user
        $this->actingAs($user);

        $post = Post::factory()->create();

        $response = $this->delete(route('posts.destroy', ['post' => $post->id]));

        $post->update([
            'deleted_user_id' => $user->id
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_post_without_authenication(): void
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('posts.destroy', ['post' => $post->id]));

        $post->update([
            'deleted_user_id' => 1
        ]);

        $response->assertStatus(302);
    }

    // public function test_export_post(): void
    // {
    //     Post::factory()->count(5)->create();

    //     $response = $this->get(route('export'));

    //     $response->assertStatus(200);
    // }

    // public function test_import_post(): void
    // {
    //     $file = UploadedFile::fake()->create('posts.csv');

    //     $response = $this->post(route('import'), ['file' => $file]);

    //     $response->assertStatus(200);
    // }

    private function createPostWithValidData($user)
    {
        $postData = [
            'title' => 'Sample Title',
            'description' => 'This is a sample description.',
            'status' => 1,
            'create_user_id' => $user->id,
            'updated_user_id' => $user->id
        ];
    }

    private function createPostWithInvalidData($user)
    {
        $postData = [
            'title' => '',
            'description' => '',
            'status' => 1,
            'create_user_id' => $user->id,
            'updated_user_id' => $user->id
        ];
    }
}
