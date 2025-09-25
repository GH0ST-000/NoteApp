<?php

namespace App\Repositories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    /**
     * GroupRepository constructor.
     */
    public function __construct(Group $model)
    {
        parent::__construct($model);
    }

    /**
     * Get groups for a specific user.
     */
    public function getForUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Find a published group by slug.
     */
    public function findPublishedBySlug(string $slug): ?Group
    {
        return $this->model->where('slug', $slug)
            ->where('is_published', true)
            ->first();
    }

    /**
     * Create a group for a specific user.
     */
    public function createForUser(int $userId, array $attributes): Group
    {
        $attributes['user_id'] = $userId;

        return $this->create($attributes);
    }

    /**
     * Check if a group belongs to a user.
     */
    public function belongsToUser(int $groupId, int $userId): bool
    {
        return $this->model->where('id', $groupId)
            ->where('user_id', $userId)
            ->exists();
    }
}
