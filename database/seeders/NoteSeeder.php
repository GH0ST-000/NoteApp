<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with their groups preloaded
        $users = User::with('groups')->get();

        // Create 3-5 notes for each user
        foreach ($users as $user) {
            // Get user's groups
            $groups = $user->groups;

            // Create notes (some with groups, some without)
            $noteCount = rand(3, 5);

            for ($i = 0; $i < $noteCount; $i++) {
                $isPinned = fake()->boolean(20); // 20% chance of being pinned
                $isPublished = fake()->boolean(30); // 30% chance of being published
                $title = fake()->sentence(rand(3, 6));
                $hasImage = fake()->boolean(40); // 40% chance of having an image

                // Randomly assign to a group or not
                $groupId = fake()->boolean(70) && $groups->count() > 0
                    ? $groups->random()->id
                    : null;

                Note::create([
                    'user_id' => $user->id,
                    'group_id' => $groupId,
                    'title' => $title,
                    'content' => fake()->paragraphs(rand(1, 5), true),
                    'is_pinned' => $isPinned,
                    'is_published' => $isPublished,
                    'slug' => $isPublished ? Str::slug($title).'-'.Str::random(8) : null,
                    'image_path' => $hasImage ? 'notes/placeholder-'.rand(1, 5).'.jpg' : null,
                ]);
            }
        }
    }
}
