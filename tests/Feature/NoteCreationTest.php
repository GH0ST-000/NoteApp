<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoteCreationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test that an authenticated user can create a note.
     */
    public function test_authenticated_user_can_create_note(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Create note data
        $noteData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'is_pinned' => false,
            'is_published' => false,
        ];

        // Send a post request to create a note
        $response = $this->post(route('notes.store'), $noteData);

        // Assert that the note was created and redirected to notes index
        $response->assertRedirect(route('notes.index'));
        $response->assertSessionHas('success', 'Note created successfully.');

        // Assert that the note exists in the database
        $this->assertDatabaseHas('notes', [
            'title' => $noteData['title'],
            'content' => $noteData['content'],
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that an authenticated user can create a note with an image.
     */
    public function test_authenticated_user_can_create_note_with_image(): void
    {
        // Create a fake disk for testing
        Storage::fake('public');

        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Create a fake image
        $image = UploadedFile::fake()->image('note_image.jpg');

        // Create note data with image
        $noteData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'is_pinned' => false,
            'is_published' => false,
            'image' => $image,
        ];

        // Send a post request to create a note with image
        $response = $this->post(route('notes.store'), $noteData);

        // Assert that the note was created and redirected to notes index
        $response->assertRedirect(route('notes.index'));
        $response->assertSessionHas('success', 'Note created successfully.');

        // Get the created note
        $note = Note::where('title', $noteData['title'])->first();

        // Assert that the note exists in the database
        $this->assertNotNull($note);
        $this->assertEquals($user->id, $note->user_id);
        $this->assertNotNull($note->image_path);

        // Assert that the image was stored
        Storage::disk('public')->assertExists($note->image_path);
    }

    /**
     * Test that an authenticated user can create a published note.
     */
    public function test_authenticated_user_can_create_published_note(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Create note data
        $noteData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'is_pinned' => false,
            'is_published' => true,
        ];

        // Send a post request to create a published note
        $response = $this->post(route('notes.store'), $noteData);

        // Assert that the note was created and redirected to notes index
        $response->assertRedirect(route('notes.index'));
        $response->assertSessionHas('success', 'Note created successfully.');

        // Get the created note
        $note = Note::where('title', $noteData['title'])->first();

        // Assert that the note exists in the database
        $this->assertNotNull($note);
        $this->assertEquals($user->id, $note->user_id);
        $this->assertTrue($note->is_published);
        $this->assertNotNull($note->slug);
    }
}
