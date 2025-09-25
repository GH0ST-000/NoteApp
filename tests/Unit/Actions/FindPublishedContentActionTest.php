<?php

namespace Tests\Unit\Actions;

use App\Actions\Published\FindPublishedContentAction;
use App\Models\Group;
use App\Models\Note;
use App\Repositories\GroupRepositoryInterface;
use App\Repositories\NoteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class FindPublishedContentActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that a published note can be found by slug.
     */
    public function test_can_find_published_note_by_slug(): void
    {
        // Mock the repositories
        $noteRepository = Mockery::mock(NoteRepositoryInterface::class);
        $groupRepository = Mockery::mock(GroupRepositoryInterface::class);

        // Create a published note
        $note = new Note([
            'id' => 1,
            'title' => 'Test Note',
            'content' => 'Test Content',
            'is_published' => true,
            'slug' => 'test-note-slug',
            'user_id' => 1,
        ]);

        // Set up expectations
        $noteRepository->shouldReceive('findPublishedBySlug')
            ->once()
            ->with('test-note-slug')
            ->andReturn($note);

        // Group repository should not be called
        $groupRepository->shouldNotReceive('findPublishedBySlug');

        // Create the action
        $action = new FindPublishedContentAction($noteRepository, $groupRepository);

        // Execute the action
        $result = $action->execute('test-note-slug');

        // Assert that the result is correct
        $this->assertIsArray($result);
        $this->assertEquals('note', $result['type']);
        $this->assertInstanceOf(Note::class, $result['data']);
        $this->assertEquals('Test Note', $result['data']->title);
        $this->assertEquals('test-note-slug', $result['data']->slug);
    }

    /**
     * Test that a published group can be found by slug.
     */
    public function test_can_find_published_group_by_slug(): void
    {
        // Mock the repositories
        $noteRepository = Mockery::mock(NoteRepositoryInterface::class);
        $groupRepository = Mockery::mock(GroupRepositoryInterface::class);

        // Create a published group
        $group = new Group([
            'name' => 'Test Group',
            'is_published' => true,
            'slug' => 'test-group-slug',
            'user_id' => 1,
        ]);
        // Set the ID manually
        $group->setAttribute('id', 1);

        // Create published notes for the group
        $notes = new Collection([
            new Note([
                'id' => 1,
                'title' => 'Note 1',
                'content' => 'Content 1',
                'is_published' => true,
                'slug' => 'note-1-slug',
                'group_id' => 1,
                'user_id' => 1,
            ]),
            new Note([
                'id' => 2,
                'title' => 'Note 2',
                'content' => 'Content 2',
                'is_published' => true,
                'slug' => 'note-2-slug',
                'group_id' => 1,
                'user_id' => 1,
            ]),
        ]);

        // Set up expectations
        $noteRepository->shouldReceive('findPublishedBySlug')
            ->once()
            ->with('test-group-slug')
            ->andReturn(null);

        $groupRepository->shouldReceive('findPublishedBySlug')
            ->once()
            ->with('test-group-slug')
            ->andReturn($group);

        $noteRepository->shouldReceive('getPublishedForGroup')
            ->once()
            ->with(1)
            ->andReturn($notes);

        // Create the action
        $action = new FindPublishedContentAction($noteRepository, $groupRepository);

        // Execute the action
        $result = $action->execute('test-group-slug');

        // Assert that the result is correct
        $this->assertIsArray($result);
        $this->assertEquals('group', $result['type']);
        $this->assertIsArray($result['data']);
        $this->assertInstanceOf(Group::class, $result['data']['group']);
        $this->assertEquals('Test Group', $result['data']['group']->name);
        $this->assertEquals('test-group-slug', $result['data']['group']->slug);
        $this->assertInstanceOf(Collection::class, $result['data']['notes']);
        $this->assertCount(2, $result['data']['notes']);
    }

    /**
     * Test that null is returned when no published content is found.
     */
    public function test_returns_null_when_no_published_content_found(): void
    {
        // Mock the repositories
        $noteRepository = Mockery::mock(NoteRepositoryInterface::class);
        $groupRepository = Mockery::mock(GroupRepositoryInterface::class);

        // Set up expectations
        $noteRepository->shouldReceive('findPublishedBySlug')
            ->once()
            ->with('non-existent-slug')
            ->andReturn(null);

        $groupRepository->shouldReceive('findPublishedBySlug')
            ->once()
            ->with('non-existent-slug')
            ->andReturn(null);

        // Create the action
        $action = new FindPublishedContentAction($noteRepository, $groupRepository);

        // Execute the action
        $result = $action->execute('non-existent-slug');

        // Assert that the result is null
        $this->assertNull($result);
    }
}
