<?php

namespace Tests\Unit\Actions;

use App\Actions\Notes\CreateNoteAction;
use App\DTOs\NoteDTO;
use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateNoteActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that a note can be created.
     */
    public function test_note_can_be_created(): void
    {
        // Mock the repository
        $repository = Mockery::mock(NoteRepositoryInterface::class);

        // Create a DTO
        $noteDTO = new NoteDTO(
            'Test Title',
            'Test Content',
            1,
            true,
            false
        );

        // Set up expectations
        $repository->shouldReceive('createForUser')
            ->once()
            ->with(1, [
                'title' => 'Test Title',
                'content' => 'Test Content',
                'group_id' => 1,
                'is_pinned' => true,
                'is_published' => false,
                'slug' => null,
                'image_path' => null,
            ])
            ->andReturn(new Note([
                'id' => 1,
                'title' => 'Test Title',
                'content' => 'Test Content',
                'group_id' => 1,
                'is_pinned' => true,
                'is_published' => false,
                'slug' => null,
                'image_path' => null,
                'user_id' => 1,
            ]));

        // Create the action
        $action = new CreateNoteAction($repository);

        // Execute the action
        $note = $action->execute($noteDTO, 1);

        // Assert that the note was created correctly
        $this->assertInstanceOf(Note::class, $note);
        $this->assertEquals('Test Title', $note->title);
        $this->assertEquals('Test Content', $note->content);
        $this->assertEquals(1, $note->group_id);
        $this->assertTrue($note->is_pinned);
        $this->assertFalse($note->is_published);
        $this->assertNull($note->slug);
        $this->assertNull($note->image_path);
        $this->assertEquals(1, $note->user_id);
    }

    /**
     * Test that a published note gets a slug.
     */
    public function test_published_note_gets_slug(): void
    {
        // Mock the repository
        $repository = Mockery::mock(NoteRepositoryInterface::class);

        // Create a DTO for a published note
        $noteDTO = new NoteDTO(
            'Test Title',
            'Test Content',
            null,
            false,
            true
        );

        // Set up expectations - we can't predict the exact slug, so use Mockery::on
        $repository->shouldReceive('createForUser')
            ->once()
            ->with(1, Mockery::on(function ($data) {
                return $data['title'] === 'Test Title' &&
                       $data['content'] === 'Test Content' &&
                       $data['group_id'] === null &&
                       $data['is_pinned'] === false &&
                       $data['is_published'] === true &&
                       $data['image_path'] === null &&
                       $data['slug'] !== null &&
                       strpos($data['slug'], 'test-title') === 0;
            }))
            ->andReturn(new Note([
                'id' => 1,
                'title' => 'Test Title',
                'content' => 'Test Content',
                'group_id' => null,
                'is_pinned' => false,
                'is_published' => true,
                'slug' => 'test-title-12345678',
                'image_path' => null,
                'user_id' => 1,
            ]));

        // Create the action
        $action = new CreateNoteAction($repository);

        // Execute the action
        $note = $action->execute($noteDTO, 1);

        // Assert that the note was created correctly and has a slug
        $this->assertInstanceOf(Note::class, $note);
        $this->assertEquals('Test Title', $note->title);
        $this->assertTrue($note->is_published);
        $this->assertNotNull($note->slug);
        $this->assertStringStartsWith('test-title', $note->slug);
    }
}
