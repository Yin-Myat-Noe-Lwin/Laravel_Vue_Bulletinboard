<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $user = User::factory()->create();

        return [
            'title' => fake()->sentence(6),
            'description' => fake()->text(200),
            'status' => rand(0,1),
            'create_user_id' => 1,
            'updated_user_id' => 1,
            'deleted_user_id' => null
        ];
    }
}
