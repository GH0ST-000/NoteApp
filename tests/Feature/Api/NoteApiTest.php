<?php

namespace Tests\Feature\Api;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test that an authenticated user can create a note via the API.
     */
    public function test_authenticated_user_can_create_note_via_api(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create note data
        $noteData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'is_pinned' => false,
            'is_published' => false,
        ];

        // Send a post request to create a note via API
        $response = $this->actingAs($user)
            ->postJson('/api/notes', $noteData);

        // Assert that the response is successful and contains the expected data
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Note created successfully',
                'data' => [
                    'title' => $noteData['title'],
                    'content' => $noteData['content'],
                    'is_pinned' => false,
                    'is_published' => false,
                    'user_id' => $user->id,
                ],
            ]);

        // Assert that the note exists in the database
        $this->assertDatabaseHas('notes', [
            'title' => $noteData['title'],
            'content' => $noteData['content'],
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that an authenticated user can retrieve their notes via the API.
     */
    public function test_authenticated_user_can_get_notes_via_api(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create some notes for the user
        Note::factory()->count(3)->create([
            'user_id' => $user->id,
            'is_pinned' => false,
        ]);

        // Create a pinned note
        Note::factory()->create([
            'user_id' => $user->id,
            'is_pinned' => true,
        ]);

        // Send a get request to retrieve notes via API
        $response = $this->actingAs($user)
            ->getJson('/api/notes');

        // Assert that the response is successful and contains 4 notes
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(4, 'data');

        // Assert that the pinned note is first in the list (ordered by is_pinned desc)
        $response->assertJson([
            'data' => [
                [
                    'is_pinned' => true,
                ],
            ],
        ]);
    }

    /**
     * Test that an authenticated user can filter notes by is_pinned via the API.
     */
    public function test_authenticated_user_can_filter_pinned_notes_via_api(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create some regular notes for the user
        Note::factory()->count(3)->create([
            'user_id' => $user->id,
            'is_pinned' => false,
        ]);

        // Create some pinned notes
        Note::factory()->count(2)->create([
            'user_id' => $user->id,
            'is_pinned' => true,
        ]);

        // Send a get request to retrieve only pinned notes via API
        $response = $this->actingAs($user)
            ->getJson('/api/notes?is_pinned=true');

        // Assert that the response is successful and contains only the 2 pinned notes
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(2, 'data');

        // Assert that all returned notes are pinned
        $response->assertJson([
            'data' => [
                [
                    'is_pinned' => true,
                ],
                [
                    'is_pinned' => true,
                ],
            ],
        ]);
    }
}
