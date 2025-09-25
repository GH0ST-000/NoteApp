<?php

namespace Tests\Feature\Api;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiResponseTraitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the API returns the correct success response structure.
     */
    public function test_api_returns_correct_success_response_structure(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create some notes for the user
        Note::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // Send a request to get notes (which uses successResponse)
        $response = $this->actingAs($user)
            ->getJson('/api/notes');

        // Assert the response has the correct structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ])
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test that the API returns the correct success message response structure.
     */
    public function test_api_returns_correct_success_message_response_structure(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create note data
        $noteData = [
            'title' => 'Test Note',
            'content' => 'Test Content',
            'is_pinned' => false,
            'is_published' => false,
        ];

        // Send a request to create a note (which uses successMessageResponse)
        $response = $this->actingAs($user)
            ->postJson('/api/notes', $noteData);

        // Assert the response has the correct structure
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Note created successfully',
            ]);
    }

    /**
     * Test that the API returns the correct error response structure.
     */
    public function test_api_returns_correct_error_response_structure(): void
    {
        // Send a request to a non-existent published content (which uses errorResponse)
        $response = $this->getJson('/api/published/non-existent-slug');

        // Assert the response has the correct structure
        $response->assertStatus(404)
            ->assertJsonStructure([
                'success',
                'message',
            ])
            ->assertJson([
                'success' => false,
                'message' => 'Published content not found',
            ]);
    }

    /**
     * Test that the API returns the correct success response with type structure.
     */
    public function test_api_returns_correct_success_response_with_type_structure(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create a published note
        $note = Note::factory()->create([
            'user_id' => $user->id,
            'is_published' => true,
            'slug' => 'test-note-slug',
        ]);

        // Send a request to get published content (which uses successResponse with type)
        $response = $this->getJson('/api/published/'.$note->slug);

        // Assert the response has the correct structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'type',
                'data',
            ])
            ->assertJson([
                'success' => true,
                'type' => 'note',
            ]);
    }
}
