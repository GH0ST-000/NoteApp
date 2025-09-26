<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders in the correct order
        $this->call([
            ImageSeeder::class, // First download placeholder images
            UserSeeder::class,  // Then create users
            GroupSeeder::class, // Then create groups for users
            NoteSeeder::class,  // Finally create notes (some assigned to groups)
        ]);
    }
}
