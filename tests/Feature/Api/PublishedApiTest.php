<?php

namespace Tests\Feature\Api;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublishedApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test that a published note can be accessed via the API.
     */
    public function test_published_note_can_be_accessed_via_api(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create a published note
        $note = Note::factory()->create([
            'user_id' => $user->id,
            'is_published' => true,
            'slug' => Str::slug($this->faker->sentence).'-'.Str::random(8),
        ]);

        // Send a get request to access the published note via API
        $response = $this->getJson('/api/published/'.$note->slug);

        // Assert that the response is successful and contains the expected data
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'type' => 'note',
                'data' => [
                    'id' => $note->id,
                    'title' => $note->title,
                    'content' => $note->content,
                    'is_published' => true,
                    'slug' => $note->slug,
                ],
            ]);
    }

    /**
     * Test that a published group with its published notes can be accessed via the API.
     */
    public function test_published_group_with_notes_can_be_accessed_via_api(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create a published group
        $group = Group::factory()->create([
            'user_id' => $user->id,
            'is_published' => true,
            'slug' => Str::slug($this->faker->words(3, true)).'-'.Str::random(8),
        ]);

        // Create some published notes in the group
        $notes = Note::factory()->count(3)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'is_published' => true,
            'slug' => function () {
                return Str::slug($this->faker->sentence).'-'.Str::random(8);
            },
        ]);

        // Create an unpublished note in the group (should not be returned)
        Note::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'is_published' => false,
        ]);

        // Send a get request to access the published group via API
        $response = $this->getJson('/api/published/'.$group->slug);

        // Assert that the response is successful and contains the expected data
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'type' => 'group',
                'data' => [
                    'group' => [
                        'id' => $group->id,
                        'name' => $group->name,
                        'is_published' => true,
                        'slug' => $group->slug,
                    ],
                ],
            ])
            ->assertJsonCount(3, 'data.notes');
    }

    /**
     * Test that accessing an unpublished note returns a 404 error.
     */
    public function test_unpublished_note_returns_404_via_api(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Create an unpublished note
        $note = Note::factory()->create([
            'user_id' => $user->id,
            'is_published' => false,
        ]);

        // Create a fake slug
        $fakeSlug = Str::slug($this->faker->sentence).'-'.Str::random(8);

        // Send a get request to access the unpublished note via API
        $response = $this->getJson('/api/published/'.$fakeSlug);

        // Assert that the response is a 404 error
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Published content not found',
            ]);
    }
}
