<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(3, 6));
        $isPinned = fake()->boolean(20); // 20% chance of being pinned
        $isPublished = fake()->boolean(30); // 30% chance of being published

        return [
            'user_id' => User::factory(),
            'group_id' => fake()->boolean(70) ? Group::factory() : null, // 70% chance of being in a group
            'title' => $title,
            'content' => fake()->paragraphs(rand(1, 5), true),
            'is_pinned' => $isPinned,
            'is_published' => $isPublished,
            'slug' => $isPublished ? Str::slug($title).'-'.Str::random(8) : null,
            'image_path' => fake()->boolean(40) ? 'notes/'.fake()->uuid().'.jpg' : null, // 40% chance of having an image
        ];
    }
}
