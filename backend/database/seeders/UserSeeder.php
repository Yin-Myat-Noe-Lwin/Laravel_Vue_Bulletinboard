<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin1',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('password'),
            'profile' => 'user-1.jpg',
            'type' => 0,
            'phone' => '09-123456789',
            'address' => 'Yangon',
            'dob' => Carbon::now(),
            'create_user_id' => 1,
            'updated_user_id' => 1
        ]);
        User::create([
            'name' => 'Admin2',
            'email' => 'admin2@gmail.com',
            'password' => bcrypt('password'),
            'profile' => 'user-2.jfif',
            'type' => 0,
            'phone' => '09-000000000',
            'address' => 'Yangon',
            'dob' => Carbon::now(),
            'create_user_id' => 2 ,
            'updated_user_id' => 2
        ]);
        User::create([
            'name' => 'User1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('password'),
            'profile' => 'user-3.jfif',
            'type' => 1,
            'phone' => '09-111111111',
            'address' => 'Bago',
            'dob' => Carbon::now(),
            'create_user_id' => 3 ,
            'updated_user_id' => 3
        ]);
        User::create([
            'name' => 'User2',
            'email' => 'user2@gmail.com',
            'password' => bcrypt('password'),
            'profile' => 'user-4.jpg',
            'type' => 1,
            'phone' => '09-222222222',
            'address' => 'Sagaing',
            'dob' => Carbon::now(),
            'create_user_id' => 4 ,
            'updated_user_id' => 4
        ]);
    }
}
