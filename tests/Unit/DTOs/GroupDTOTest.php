<?php

namespace Tests\Unit\DTOs;

use App\DTOs\GroupDTO;
use PHPUnit\Framework\TestCase;

class GroupDTOTest extends TestCase
{
    /**
     * Test that a GroupDTO can be created from an array.
     */
    public function test_group_dto_can_be_created_from_array(): void
    {
        // Create test data
        $data = [
            'name' => 'Test Group',
            'is_published' => true,
            'slug' => 'test-group-slug',
        ];

        // Create DTO from array
        $groupDTO = GroupDTO::fromArray($data);

        // Assert that the DTO has the correct properties
        $this->assertEquals($data['name'], $groupDTO->name);
        $this->assertEquals($data['is_published'], $groupDTO->isPublished);
        $this->assertEquals($data['slug'], $groupDTO->slug);
    }

    /**
     * Test that a GroupDTO can be converted to an array.
     */
    public function test_group_dto_can_be_converted_to_array(): void
    {
        // Create a DTO
        $groupDTO = new GroupDTO(
            'Test Group',
            true,
            'test-group-slug'
        );

        // Convert to array
        $array = $groupDTO->toArray();

        // Assert that the array has the correct keys and values
        $this->assertEquals('Test Group', $array['name']);
        $this->assertEquals(true, $array['is_published']);
        $this->assertEquals('test-group-slug', $array['slug']);
    }

    /**
     * Test that a GroupDTO can be created with default values.
     */
    public function test_group_dto_can_be_created_with_default_values(): void
    {
        // Create a minimal DTO
        $groupDTO = new GroupDTO(
            'Test Group'
        );

        // Assert that default values are set correctly
        $this->assertEquals('Test Group', $groupDTO->name);
        $this->assertFalse($groupDTO->isPublished);
        $this->assertNull($groupDTO->slug);
    }
}
