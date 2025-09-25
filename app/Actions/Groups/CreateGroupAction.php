<?php

namespace App\Actions\Groups;

use App\DTOs\GroupDTO;
use App\Models\Group;
use App\Repositories\GroupRepositoryInterface;
use Illuminate\Support\Str;

class CreateGroupAction
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * CreateGroupAction constructor.
     */
    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Execute the action.
     */
    public function execute(GroupDTO $groupDTO, int $userId): Group
    {
        // Generate slug if group is published
        if ($groupDTO->isPublished && ! $groupDTO->slug) {
            $groupDTO->slug = Str::slug($groupDTO->name).'-'.Str::random(8);
        }

        // Create the group
        return $this->groupRepository->createForUser($userId, $groupDTO->toArray());
    }
}
