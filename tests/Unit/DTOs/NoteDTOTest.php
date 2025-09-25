<?php

namespace Tests\Unit\DTOs;

use App\DTOs\NoteDTO;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class NoteDTOTest extends TestCase
{
    /**
     * Test that a NoteDTO can be created from an array.
     */
    public function test_note_dto_can_be_created_from_array(): void
    {
        // Create test data
        $data = [
            'title' => 'Test Title',
            'content' => 'Test Content',
            'group_id' => 1,
            'is_pinned' => true,
            'is_published' => true,
            'slug' => 'test-slug',
            'image_path' => 'path/to/image.jpg',
            'image' => UploadedFile::fake()->image('test.jpg'),
        ];

        // Create DTO from array
        $noteDTO = NoteDTO::fromArray($data);

        // Assert that the DTO has the correct properties
        $this->assertEquals($data['title'], $noteDTO->title);
        $this->assertEquals($data['content'], $noteDTO->content);
        $this->assertEquals($data['group_id'], $noteDTO->groupId);
        $this->assertEquals($data['is_pinned'], $noteDTO->isPinned);
        $this->assertEquals($data['is_published'], $noteDTO->isPublished);
        $this->assertEquals($data['slug'], $noteDTO->slug);
        $this->assertEquals($data['image_path'], $noteDTO->imagePath);
        $this->assertInstanceOf(UploadedFile::class, $noteDTO->imageFile);
    }

    /**
     * Test that a NoteDTO can be converted to an array.
     */
    public function test_note_dto_can_be_converted_to_array(): void
    {
        // Create a DTO
        $noteDTO = new NoteDTO(
            'Test Title',
            'Test Content',
            1,
            true,
            true,
            'test-slug',
            'path/to/image.jpg',
            UploadedFile::fake()->image('test.jpg')
        );

        // Convert to array
        $array = $noteDTO->toArray();

        // Assert that the array has the correct keys and values
        $this->assertEquals('Test Title', $array['title']);
        $this->assertEquals('Test Content', $array['content']);
        $this->assertEquals(1, $array['group_id']);
        $this->assertEquals(true, $array['is_pinned']);
        $this->assertEquals(true, $array['is_published']);
        $this->assertEquals('test-slug', $array['slug']);
        $this->assertEquals('path/to/image.jpg', $array['image_path']);

        // The image file should not be included in the array
        $this->assertArrayNotHasKey('image', $array);
        $this->assertArrayNotHasKey('imageFile', $array);
    }

    /**
     * Test that a NoteDTO can be created with default values.
     */
    public function test_note_dto_can_be_created_with_default_values(): void
    {
        // Create a minimal DTO
        $noteDTO = new NoteDTO(
            'Test Title',
            'Test Content'
        );

        // Assert that default values are set correctly
        $this->assertEquals('Test Title', $noteDTO->title);
        $this->assertEquals('Test Content', $noteDTO->content);
        $this->assertNull($noteDTO->groupId);
        $this->assertFalse($noteDTO->isPinned);
        $this->assertFalse($noteDTO->isPublished);
        $this->assertNull($noteDTO->slug);
        $this->assertNull($noteDTO->imagePath);
        $this->assertNull($noteDTO->imageFile);
    }
}
