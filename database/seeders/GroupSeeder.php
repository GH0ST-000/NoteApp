<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        // Create 2-3 groups for each user
        foreach ($users as $user) {
            $groupCount = rand(2, 3);

            Group::factory()
                ->count($groupCount)
                ->create([
                    'user_id' => $user->id,
                ]);
        }
    }
}
