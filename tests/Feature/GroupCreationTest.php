<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupCreationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test that an authenticated user can create a group.
     */
    public function test_authenticated_user_can_create_group(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Create group data
        $groupData = [
            'name' => $this->faker->words(3, true),
            'is_published' => false,
        ];

        // Send a post request to create a group
        $response = $this->post(route('groups.store'), $groupData);

        // Assert that the group was created and redirected to groups index
        $response->assertRedirect(route('groups.index'));
        $response->assertSessionHas('success', 'Group created successfully.');

        // Assert that the group exists in the database
        $this->assertDatabaseHas('groups', [
            'name' => $groupData['name'],
            'user_id' => $user->id,
            'is_published' => false,
        ]);
    }

    /**
     * Test that an authenticated user can create a published group.
     */
    public function test_authenticated_user_can_create_published_group(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Create group data
        $groupData = [
            'name' => $this->faker->words(3, true),
            'is_published' => true,
        ];

        // Send a post request to create a published group
        $response = $this->post(route('groups.store'), $groupData);

        // Assert that the group was created and redirected to groups index
        $response->assertRedirect(route('groups.index'));
        $response->assertSessionHas('success', 'Group created successfully.');

        // Get the created group
        $group = Group::where('name', $groupData['name'])->first();

        // Assert that the group exists in the database
        $this->assertNotNull($group);
        $this->assertEquals($user->id, $group->user_id);
        $this->assertTrue($group->is_published);
        $this->assertNotNull($group->slug);
    }

    /**
     * Test that notes can be assigned to a group.
     */
    public function test_notes_can_be_assigned_to_group(): void
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Create a group
        $group = Group::factory()->create([
            'user_id' => $user->id,
        ]);

        // Create note data with group assignment
        $noteData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'group_id' => $group->id,
            'is_pinned' => false,
            'is_published' => false,
        ];

        // Send a post request to create a note in the group
        $response = $this->post(route('notes.store'), $noteData);

        // Assert that the note was created and redirected to notes index
        $response->assertRedirect(route('notes.index'));
        $response->assertSessionHas('success', 'Note created successfully.');

        // Assert that the note exists in the database and is assigned to the group
        $this->assertDatabaseHas('notes', [
            'title' => $noteData['title'],
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
    }
}
