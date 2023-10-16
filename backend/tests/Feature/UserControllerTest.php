<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class UserControllerTest extends TestCase
{
    use DatabaseTruncation;

    public function test_create_user_with_authenication():void
    {
        $user = User::factory()->create();

        //current logged in user
        $this->actingAs($user);

         // Scenario 1: Successful User Creation
        $response = $this->createUserWithValidData($user);

        $response->assertStatus(200);

        // Scenario 2: Validation Errors
        $response = $this->createUserWithInvalidData($user);

        $response->assertStatus(302);
    }

    public function test_create_user_without_authenication():void
    {
        //encrypt created user's password
         $password = bcrypt('Password@');

        //created user's profile image
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
            'create_user_id' => 1,
            'updated_user_id' => 1
        ];

        // Scenario 1: create user without authenication
        $response = $this->post(route('users.store'), $userData);

        $response->assertStatus(302);
    }

    public function test_signup():void
    {
        //encrypt signed up user's password
        $password = bcrypt('Password@');

        //signed up user's profile image
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
            'create_user_id' => 1,
            'updated_user_id' => 1
        ];

        $response = $this->post(route('signup'), $userData);

        $response->assertStatus(200);

        $user = User::where('email', $userData['email'])->first();

        // Update create_user_id and updated_user_id with the user's id.
        $user->update([
            'create_user_id' => $user->id,
            'updated_user_id' => $user->id,
        ]);

    }

    public function test_update_user(): void
    {
        $user = User::factory()->create();

        //current logged in user
        $this->actingAs($user);

        $password = bcrypt('Taylor$wift');

        $profileImage = UploadedFile::fake()->image('user-4.jpg');

        //update logged in user data
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

    public function test_delete_user_with_authenication():void
    {
        $user = User::factory()->create();

        //current logged in user
        $this->actingAs($user);

        //will be deleted user
        $userThatWillBeDeleted = User::factory()->create();

        $response = $this->delete(route('users.destroy', ['user' => $userThatWillBeDeleted->id]));

        $userThatWillBeDeleted->update([
            'deleted_user_id' => $user->id
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_user_without_authenication():void
    {
        //will be deleted user
        $userThatWillBeDeleted = User::factory()->create();

        $response = $this->delete(route('users.destroy', ['user' => $userThatWillBeDeleted->id]));

        $userThatWillBeDeleted->update([
            'deleted_user_id' => 1
        ]);

        $response->assertStatus(302);
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

        //current logged in user
        $this->actingAs($adminUser);

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
    }

    public function test_view_limited_users_when_logged_in_with_user_role(): void
    {
        $regularUser =  User::factory()->create([
            'type' => '1'
        ]);

        //current logged in user
        $this->actingAs($regularUser);

        //show users only created by that user
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

    public function test_view_users_without_authenication(): void
    {
        //user may be user type admin or user viewed by unauthenicated user
        $regularUser = User::factory()->create();

        $response = $this->get(route('users.show', ['user' => $regularUser->id]));

        $response->assertStatus(302);
    }

    public function test_change_user_password_with_authenication(): void
    {
        $user = User::factory()->create();

        //current logged in user
        $this->actingAs($user);

        //encrypt created user's password
        $password = bcrypt('Password@');

        $userData = [
            'old_password' => 'password',
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post(route('users.changePassword', ['user' => $user->id]), $userData);

        $response->assertStatus(200);
    }

    public function test_change_user_password_without_authenication(): void
    {
        //encrypt created user's password
        $password = bcrypt('Password@');

        $userData = [
            'old_password' => 'password',
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post(route('users.changePassword', ['user' => 1]), $userData);

        $response->assertStatus(302);
    }

    private function createUserWithValidData($user)
    {
        //encrypt created user's password
        $password = bcrypt('Password@');

        //created user's profile image
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
            'updated_user_id' => $user->id,
        ];

        return $this->post(route('users.store'), $userData);
    }

    private function createUserWithInvalidData($user)
    {
        //encrypt created user's password
        $password = bcrypt('Password@');

        //created user's profile image
        $profileImage = UploadedFile::fake()->image('user-4.jpg');

        $userData = [
            'name' => '',
            'email' => 'momogmail.com',
            'password' => $password,
            'password_confirmation' => $password,
            'profile' => $profileImage,
            'type' => '0',
            'phone' => '09-123456789',
            'address' => 'Yangon',
            'dob' => Carbon::now(),
            'create_user_id' => $user->id,
            'updated_user_id' => $user->id,
        ];

        return $this->post(route('users.store'), $userData);
    }
}
