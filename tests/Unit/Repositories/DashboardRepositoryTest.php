<?php

namespace Tests\Unit\Repositories;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use App\Repositories\DashboardRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DashboardRepository;
    }

    public function test_get_notes_count(): void
    {
        $user = User::factory()->create();
        Note::factory()->count(5)->for($user)->create();

        $otherUser = User::factory()->create();
        Note::factory()->count(3)->for($otherUser)->create();

        $count = $this->repository->getNotesCount($user->id);
        $this->assertEquals(5, $count);
    }

    public function test_get_groups_count(): void
    {
        $user = User::factory()->create();
        Group::factory()->count(3)->for($user)->create();

        $otherUser = User::factory()->create();
        Group::factory()->count(2)->for($otherUser)->create();

        $count = $this->repository->getGroupsCount($user->id);
        $this->assertEquals(3, $count);
    }

    public function test_get_recent_notes(): void
    {
        $user = User::factory()->create();

        $group = Group::factory()->for($user)->create(['name' => 'Test Group']);
        $noteWithGroup = Note::factory()->for($user)->for($group)->create([
            'title' => 'Note with group',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Note::factory()->count(10)->for($user)->create([
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $recentNotes = $this->repository->getRecentNotes($user->id);

        $this->assertCount(5, $recentNotes);

        $this->assertTrue($recentNotes->first()->is($noteWithGroup));

        $this->assertTrue($recentNotes->first()->relationLoaded('group'));
        $this->assertEquals('Test Group', $recentNotes->first()->group->name);
    }
}
