<?php

namespace App\Actions\Groups;

use App\Repositories\GroupRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

class DeleteGroupAction
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * DeleteGroupAction constructor.
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
    public function execute(int $groupId, int $userId): bool
    {
        // Check if the group belongs to the user
        if (! $this->groupRepository->belongsToUser($groupId, $userId)) {
            throw new AuthorizationException('Unauthorized action.');
        }

        // Delete the group
        return $this->groupRepository->delete($groupId);
    }
}
