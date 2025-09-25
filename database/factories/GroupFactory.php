<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);
        $isPublished = fake()->boolean(30); // 30% chance of being published

        return [
            'user_id' => User::factory(),
            'name' => ucfirst($name),
            'is_published' => $isPublished,
            'slug' => $isPublished ? Str::slug($name).'-'.Str::random(8) : null,
        ];
    }
}
