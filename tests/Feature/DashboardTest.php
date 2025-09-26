<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use App\Repositories\DashboardRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the dashboard page loads correctly.
     */
    public function test_dashboard_page_loads_correctly(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);

        $response->assertViewHas(['notesCount', 'groupsCount', 'recentNotes']);
    }

    /**
     * Test that the dashboard shows correct counts.
     */
    public function test_dashboard_shows_correct_counts(): void
    {
        $user = User::factory()->create();
        Note::factory()->count(3)->for($user)->create();
        Group::factory()->count(2)->for($user)->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('notesCount', 3);
        $response->assertViewHas('groupsCount', 2);
    }

    /**
     * Test that the dashboard shows recent notes.
     */
    public function test_dashboard_shows_recent_notes(): void
    {
        $user = User::factory()->create();

        $group = Group::factory()->for($user)->create(['name' => 'Test Group']);
        $noteWithGroup = Note::factory()->for($user)->for($group)->create([
            'title' => 'Note with group',
            'is_pinned' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Note::factory()->count(10)->for($user)->create([
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);

        $response->assertViewHas('recentNotes');

        $recentNotes = $response->viewData('recentNotes');
        $this->assertTrue($recentNotes->contains('title', 'Note with group'), 'Recent notes does not contain our test note');

        $this->assertTrue($recentNotes->first()->is($noteWithGroup), 'First recent note should be our test note');
        $this->assertEquals('Test Group', $recentNotes->first()->group->name);
    }

    /**
     * Test that eager loading is used to prevent lazy loading violations.
     */
    public function test_eager_loading_prevents_lazy_loading_violations(): void
    {
        $user = User::factory()->create();
        Note::factory()->count(5)->for($user)->create();
        $group = Group::factory()->for($user)->create(['name' => 'Test Group']);
        $noteWithGroup = Note::factory()->for($user)->for($group)->create();

        $queriesExecuted = 0;
        DB::listen(function ($query) use (&$queriesExecuted): void {
            $queriesExecuted++;
        });

        $this->actingAs($user)->get('/dashboard');

        $this->assertLessThan(10, $queriesExecuted, 'Too many queries executed');
    }

    /**
     * Test that the dashboard repository works correctly.
     */
    public function test_dashboard_repository_returns_correct_data(): void
    {
        $user = User::factory()->create();
        Note::factory()->count(3)->for($user)->create();
        $group = Group::factory()->for($user)->create();
        $noteWithGroup = Note::factory()->for($user)->for($group)->create();

        $repository = app(DashboardRepositoryInterface::class);

        $notesCount = $repository->getNotesCount($user->id);
        $groupsCount = $repository->getGroupsCount($user->id);
        $recentNotes = $repository->getRecentNotes($user->id);

        $this->assertEquals(4, $notesCount);
        $this->assertEquals(1, $groupsCount);
        $this->assertInstanceOf(Collection::class, $recentNotes);
        $this->assertLessThanOrEqual(5, $recentNotes->count());

        $noteWithGroupFromRepo = $recentNotes->where('id', $noteWithGroup->id)->first();
        $this->assertTrue($noteWithGroupFromRepo->relationLoaded('group'));
    }
}
