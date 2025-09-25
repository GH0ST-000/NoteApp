<?php

namespace Tests\Unit\Actions;

use App\Actions\Groups\CreateGroupAction;
use App\DTOs\GroupDTO;
use App\Models\Group;
use App\Repositories\GroupRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateGroupActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that a group can be created.
     */
    public function test_group_can_be_created(): void
    {
        // Mock the repository
        $repository = Mockery::mock(GroupRepositoryInterface::class);

        // Create a DTO
        $groupDTO = new GroupDTO(
            'Test Group',
            false
        );

        // Set up expectations
        $repository->shouldReceive('createForUser')
            ->once()
            ->with(1, [
                'name' => 'Test Group',
                'is_published' => false,
                'slug' => null,
            ])
            ->andReturn(new Group([
                'id' => 1,
                'name' => 'Test Group',
                'is_published' => false,
                'slug' => null,
                'user_id' => 1,
            ]));

        // Create the action
        $action = new CreateGroupAction($repository);

        // Execute the action
        $group = $action->execute($groupDTO, 1);

        // Assert that the group was created correctly
        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals('Test Group', $group->name);
        $this->assertFalse($group->is_published);
        $this->assertNull($group->slug);
        $this->assertEquals(1, $group->user_id);
    }

    /**
     * Test that a published group gets a slug.
     */
    public function test_published_group_gets_slug(): void
    {
        // Mock the repository
        $repository = Mockery::mock(GroupRepositoryInterface::class);

        // Create a DTO for a published group
        $groupDTO = new GroupDTO(
            'Test Group',
            true
        );

        // Set up expectations - we can't predict the exact slug, so use Mockery::on
        $repository->shouldReceive('createForUser')
            ->once()
            ->with(1, Mockery::on(function ($data) {
                return $data['name'] === 'Test Group' &&
                       $data['is_published'] === true &&
                       $data['slug'] !== null &&
                       strpos($data['slug'], 'test-group') === 0;
            }))
            ->andReturn(new Group([
                'id' => 1,
                'name' => 'Test Group',
                'is_published' => true,
                'slug' => 'test-group-12345678',
                'user_id' => 1,
            ]));

        // Create the action
        $action = new CreateGroupAction($repository);

        // Execute the action
        $group = $action->execute($groupDTO, 1);

        // Assert that the group was created correctly and has a slug
        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals('Test Group', $group->name);
        $this->assertTrue($group->is_published);
        $this->assertNotNull($group->slug);
        $this->assertStringStartsWith('test-group', $group->slug);
        $this->assertEquals(1, $group->user_id);
    }
}
