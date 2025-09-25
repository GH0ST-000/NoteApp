<?php

namespace Tests\Unit\Actions;

use App\Actions\Groups\UpdateGroupAction;
use App\DTOs\GroupDTO;
use App\Models\Group;
use App\Repositories\GroupRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Mockery;
use PHPUnit\Framework\TestCase;

class UpdateGroupActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that a group can be updated.
     */
    public function test_group_can_be_updated(): void
    {
        // Mock the repository
        $repository = Mockery::mock(GroupRepositoryInterface::class);

        // Create a DTO
        $groupDTO = new GroupDTO(
            'Updated Group',
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
            ->andReturn(new Group([
                'id' => 1,
                'name' => 'Original Group',
                'is_published' => false,
                'slug' => null,
                'user_id' => 1,
            ]));

        $repository->shouldReceive('update')
            ->once()
            ->with(1, [
                'name' => 'Updated Group',
                'is_published' => false,
                'slug' => null,
            ])
            ->andReturn(new Group([
                'id' => 1,
                'name' => 'Updated Group',
                'is_published' => false,
                'slug' => null,
                'user_id' => 1,
            ]));

        // Create the action
        $action = new UpdateGroupAction($repository);

        // Execute the action
        $group = $action->execute(1, $groupDTO, 1);

        // Assert that the group was updated correctly
        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals('Updated Group', $group->name);
        $this->assertFalse($group->is_published);
        $this->assertNull($group->slug);
        $this->assertEquals(1, $group->user_id);
    }

    /**
     * Test that an unauthorized user cannot update a group.
     */
    public function test_unauthorized_user_cannot_update_group(): void
    {
        // Mock the repository
        $repository = Mockery::mock(GroupRepositoryInterface::class);

        // Create a DTO
        $groupDTO = new GroupDTO(
            'Updated Group'
        );

        // Set up expectations
        $repository->shouldReceive('belongsToUser')
            ->once()
            ->with(1, 2)
            ->andReturn(false);

        // Create the action
        $action = new UpdateGroupAction($repository);

        // Expect an exception
        $this->expectException(AuthorizationException::class);

        // Execute the action
        $action->execute(1, $groupDTO, 2);
    }

    /**
     * Test that a group can be published (gets a slug).
     */
    public function test_group_gets_slug_when_published(): void
    {
        // Mock the repository
        $repository = Mockery::mock(GroupRepositoryInterface::class);

        // Create a DTO for a group that will be published
        $groupDTO = new GroupDTO(
            'Test Group',
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
            ->andReturn(new Group([
                'id' => 1,
                'name' => 'Test Group',
                'is_published' => false, // Was not published
                'slug' => null,
                'user_id' => 1,
            ]));

        $repository->shouldReceive('update')
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
        $action = new UpdateGroupAction($repository);

        // Execute the action
        $group = $action->execute(1, $groupDTO, 1);

        // Assert that the group was updated correctly and has a slug
        $this->assertInstanceOf(Group::class, $group);
        $this->assertTrue($group->is_published);
        $this->assertNotNull($group->slug);
        $this->assertStringStartsWith('test-group', $group->slug);
    }

    /**
     * Test that a group loses its slug when unpublished.
     */
    public function test_group_loses_slug_when_unpublished(): void
    {
        // Mock the repository
        $repository = Mockery::mock(GroupRepositoryInterface::class);

        // Create a DTO for a group that will be unpublished
        $groupDTO = new GroupDTO(
            'Test Group',
            false // Now unpublished
        );

        // Set up expectations
        $repository->shouldReceive('belongsToUser')
            ->once()
            ->with(1, 1)
            ->andReturn(true);

        $repository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(new Group([
                'id' => 1,
                'name' => 'Test Group',
                'is_published' => true, // Was published
                'slug' => 'test-group-12345678',
                'user_id' => 1,
            ]));

        $repository->shouldReceive('update')
            ->once()
            ->with(1, [
                'name' => 'Test Group',
                'is_published' => false,
                'slug' => null, // Slug should be removed
            ])
            ->andReturn(new Group([
                'id' => 1,
                'name' => 'Test Group',
                'is_published' => false,
                'slug' => null,
                'user_id' => 1,
            ]));

        // Create the action
        $action = new UpdateGroupAction($repository);

        // Execute the action
        $group = $action->execute(1, $groupDTO, 1);

        // Assert that the group was updated correctly and has no slug
        $this->assertInstanceOf(Group::class, $group);
        $this->assertFalse($group->is_published);
        $this->assertNull($group->slug);
    }
}
