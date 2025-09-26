<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use App\Repositories\GroupRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GroupsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the groups index page loads correctly.
     */
    public function test_groups_index_page_loads_correctly(): void
    {
        $user = User::factory()->create();
        Group::factory()->count(3)->for($user)->create();

        $response = $this->actingAs($user)->get('/groups');

        $response->assertStatus(200);
    }

    /**
     * Test that the groups show page loads correctly.
     */
    public function test_groups_show_page_loads_correctly(): void
    {
        $user = User::factory()->create();
        $group = Group::factory()->for($user)->create();
        Note::factory()->count(5)->for($user)->for($group)->create();

        $response = $this->actingAs($user)->get("/groups/{$group->id}");

        $response->assertStatus(200);
    }

    /**
     * Test eager loading prevents lazy loading violations.
     */
    public function test_eager_loading_prevents_lazy_loading_violations(): void
    {
        $user = User::factory()->create();
        $group = Group::factory()->for($user)->create();
        Note::factory()->count(5)->for($user)->for($group)->create();

        $queriesExecuted = 0;
        DB::listen(function ($query) use (&$queriesExecuted): void {
            $queriesExecuted++;
        });

        $this->actingAs($user)->get("/groups/{$group->id}");

        $this->assertLessThan(10, $queriesExecuted, 'Too many queries executed');
    }

    /**
     * Test that the group repository returns groups with eager loaded notes.
     */
    public function test_group_repository_eager_loads_notes(): void
    {
        $user = User::factory()->create();
        $group = Group::factory()->for($user)->create();
        Note::factory()->count(5)->for($user)->for($group)->create();

        $repository = app(GroupRepositoryInterface::class);
        $groupWithNotes = $repository->findWithNotes($group->id);

        $this->assertTrue($groupWithNotes->relationLoaded('notes'), 'Notes relationship is not eager loaded');
        $this->assertInstanceOf(Collection::class, $groupWithNotes->notes);
        $this->assertEquals(5, $groupWithNotes->notes->count());
    }

    /**
     * Test that the group repository returns groups with notes count.
     */
    public function test_group_repository_returns_notes_count(): void
    {
        $user = User::factory()->create();
        $group = Group::factory()->for($user)->create();
        Note::factory()->count(3)->for($user)->for($group)->create();

        $repository = app(GroupRepositoryInterface::class);
        $groups = $repository->getForUserWithNotesCount($user->id);

        $this->assertEquals(1, $groups->count());
        $this->assertEquals(3, $groups->first()->notes_count);
    }

    /**
     * Test that the published group page loads correctly.
     */
    public function test_published_group_page_loads_correctly(): void
    {
        $user = User::factory()->create();
        $group = Group::factory()->for($user)->create([
            'is_published' => true,
            'slug' => 'test-group-slug',
        ]);
        for ($i = 1; $i <= 3; $i++) {
            Note::factory()->for($user)->for($group)->create([
                'is_published' => true,
                'slug' => "test-note-slug-{$i}",
            ]);
        }

        $response = $this->get("/g/{$group->slug}");

        $response->assertStatus(200);
    }
}
