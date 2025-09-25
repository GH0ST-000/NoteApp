<?php

namespace App\Actions\Groups;

use App\Repositories\GroupRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetUserGroupsAction
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * GetUserGroupsAction constructor.
     */
    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Execute the action.
     */
    public function execute(int $userId): Collection
    {
        return $this->groupRepository->getForUser($userId);
    }
}
