<?php

namespace App\Actions\Groups;

use App\DTOs\GroupDTO;
use App\Models\Group;
use App\Repositories\GroupRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;

class UpdateGroupAction
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * UpdateGroupAction constructor.
     */
    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Execute the action.
     *
     * @throws AuthorizationException
     */
    public function execute(int $groupId, GroupDTO $groupDTO, int $userId): Group
    {
        // Check if the group belongs to the user
        if (! $this->groupRepository->belongsToUser($groupId, $userId)) {
            throw new AuthorizationException('Unauthorized action.');
        }

        // Get the group
        $group = $this->groupRepository->find($groupId);

        // Update slug if publishing status changed
        if ($groupDTO->isPublished && ! $group->is_published) {
            // Group is being published
            $groupDTO->slug = Str::slug($groupDTO->name).'-'.Str::random(8);
        } elseif (! $groupDTO->isPublished && $group->is_published) {
            // Group is being unpublished
            $groupDTO->slug = null;
        }

        // Update the group
        return $this->groupRepository->update($groupId, $groupDTO->toArray());
    }
}
