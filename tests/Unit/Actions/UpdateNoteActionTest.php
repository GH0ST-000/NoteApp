<?php

namespace Tests\Unit\Actions;

use App\Actions\Notes\UpdateNoteAction;
use App\DTOs\NoteDTO;
use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Mockery;
use PHPUnit\Framework\TestCase;

class UpdateNoteActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that a note can be updated.
     */
    public function test_note_can_be_updated(): void
    {
        // Mock the repository
        $repository = Mockery::mock(NoteRepositoryInterface::class);

        // Create a DTO
        $noteDTO = new NoteDTO(
            'Updated Title',
            'Updated Content',
            2,
            true,
            false
        );

        // Set up expectations
        $repository->shouldReceive('belongsToUser')
            ->once()
            ->with(1, 1)
            ->andReturn(true);

        $repository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(new Note([
                'id' => 1,
                'title' => 'Original Title',
                'content' => 'Original Content',
                'group_id' => 1,
                'is_pinned' => false,
                'is_published' => false,
                'slug' => null,
                'image_path' => null,
                'user_id' => 1,
            ]));

        $repository->shouldReceive('update')
            ->once()
            ->with(1, [
                'title' => 'Updated Title',
                'content' => 'Updated Content',
                'group_id' => 2,
                'is_pinned' => true,
                'is_published' => false,
                'slug' => null,
                'image_path' => null,
            ])
            ->andReturn(new Note([
                'id' => 1,
                'title' => 'Updated Title',
                'content' => 'Updated Content',
                'group_id' => 2,
                'is_pinned' => true,
                'is_published' => false,
                'slug' => null,
                'image_path' => null,
                'user_id' => 1,
            ]));

        // Create the action
        $action = new UpdateNoteAction($repository);

        // Execute the action
        $note = $action->execute(1, $noteDTO, 1);

        // Assert that the note was updated correctly
        $this->assertInstanceOf(Note::class, $note);
        $this->assertEquals('Updated Title', $note->title);
        $this->assertEquals('Updated Content', $note->content);
        $this->assertEquals(2, $note->group_id);
        $this->assertTrue($note->is_pinned);
        $this->assertFalse($note->is_published);
        $this->assertNull($note->slug);
        $this->assertNull($note->image_path);
        $this->assertEquals(1, $note->user_id);
    }

    /**
     * Test that an unauthorized user cannot update a note.
     */
    public function test_unauthorized_user_cannot_update_note(): void
    {
        // Mock the repository
        $repository = Mockery::mock(NoteRepositoryInterface::class);

        // Create a DTO
        $noteDTO = new NoteDTO(
            'Updated Title',
            'Updated Content'
        );

        // Set up expectations
        $repository->shouldReceive('belongsToUser')
            ->once()
            ->with(1, 2)
            ->andReturn(false);

        // Create the action
        $action = new UpdateNoteAction($repository);

        // Expect an exception
        $this->expectException(AuthorizationException::class);

        // Execute the action
        $action->execute(1, $noteDTO, 2);
    }

    /**
     * Test that a note can be published (gets a slug).
     */
    public function test_note_gets_slug_when_published(): void
    {
        // Mock the repository
        $repository = Mockery::mock(NoteRepositoryInterface::class);

        // Create a DTO for a note that will be published
        $noteDTO = new NoteDTO(
            'Test Title',
            'Test Content',
            null,
            false,
            true // Now published
        );

        // Set up expectations
        $repository->shouldReceive('belongsToUser')
            ->once()
            ->with(1, 1)
            ->andReturn(true);

        $repository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(new Note([
                'id' => 1,
                'title' => 'Test Title',
                'content' => 'Test Content',
                'group_id' => null,
                'is_pinned' => false,
                'is_published' => false, // Was not published
                'slug' => null,
                'image_path' => null,
                'user_id' => 1,
            ]));

        $repository->shouldReceive('update')
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
        $action = new UpdateNoteAction($repository);

        // Execute the action
        $note = $action->execute(1, $noteDTO, 1);

        // Assert that the note was updated correctly and has a slug
        $this->assertInstanceOf(Note::class, $note);
        $this->assertTrue($note->is_published);
        $this->assertNotNull($note->slug);
        $this->assertStringStartsWith('test-title', $note->slug);
    }
}
