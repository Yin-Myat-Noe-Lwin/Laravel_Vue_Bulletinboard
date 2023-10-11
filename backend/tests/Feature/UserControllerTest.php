<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class UserControllerTest extends TestCase
{
    use DatabaseTruncation;

    public function test_create_user():void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $password = bcrypt('Password@');

        $profileImage = UploadedFile::fake()->image('user-4.jpg');

        $userData = [
            'name' => 'Hirai Momo',
            'email' => 'momo@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
            'profile' => $profileImage,
            'type' => '0',
            'phone' => '09-123456789',
            'address' => 'Yangon',
            'dob' => Carbon::now(),
            'create_user_id' => $user->id,
            'updated_user_id' => $user->id
        ];

        $response = $this->post(route('users.store'), $userData);

        $response->assertStatus(200);
    }

    public function test_update_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $password = bcrypt('Taylor$wift');

        $profileImage = UploadedFile::fake()->image('user-4.jpg');

        $updatedUserData = [
            'name' => 'Taylor Swift',
            'email' => 'taylorswift@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
            'profile' => $profileImage,
            'type' => '1',
            'phone' => '09-090909099',
            'address' => 'Yangon',
            'dob' => Carbon::now(),
            'create_user_id' => $user->id,
            'updated_user_id' => $user->id
        ];

        $response = $this->put(route('users.update', ['user' => $user->id]), $updatedUserData);

        $response->assertStatus(200);
    }

    public function test_delete_user():void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $userThatWillBeDeleted = User::factory()->create();

        $response = $this->delete(route('users.destroy', ['user' => $userThatWillBeDeleted->id]));

        $response->assertStatus(200);
    }

    public function test_view_all_users_when_not_logged_in(): void
    {
        $response = $this->get(route('showAllUsers'));

        $response->assertStatus(200);
    }

    public function test_view_all_users_when_logged_in_with_admin_role(): void
    {
        //create user type admin to view all users
        $adminUser =  User::factory()->create([
            'type' => '0'
        ]);

        $this->actingAs($adminUser);

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
    }

    public function test_view_limited_users_when_logged_in_with_user_role(): void
    {
        $regularUser =  User::factory()->create([
            'type' => '1'
        ]);

        $this->actingAs($regularUser);

        User::factory()->count(5)->create([
            'create_user_id' =>  $regularUser->id
        ]);

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_all_users(): void
    {
        //create user type admin to view all users
        $adminUser =  User::factory()->create([
            'type' => '0'
        ]);

        $this->actingAs($adminUser);

        //another user may be user type admin or user viewed by admin
        $regularUser = User::factory()->create();

        $response = $this->get(route('users.show', ['user' => $regularUser->id]));

        $response->assertStatus(200);
    }

    public function test_user_can_view_only_its_created_users(): void
    {
        //create user type user to view only its created users
        $user =  User::factory()->create([
            'type' => '1'
        ]);

        $this->actingAs($user);

        //another user may be user type admin or user viewed by user
        $regularUser = User::factory()->create([
            'create_user_id' => $user->id ,
            'updated_user_id' => $user->id
        ]);

        $response = $this->get(route('users.show', ['user' => $regularUser->id]));

        $response->assertStatus(200);
    }

    public function test_user_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('users.changePassword', ['user' => $user->id]));

        $response->assertStatus(200);
    }
}
