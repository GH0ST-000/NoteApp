<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DashboardRepository implements DashboardRepositoryInterface
{
    /**
     * Get user with eager loaded relationships for dashboard.
     */
    public function getUserWithRelationships(int $userId): User
    {
        return User::findOrFail($userId);
    }

    /**
     * Get recent notes for a user with group relationship.
     */
    public function getRecentNotes(int $userId, int $limit = 5): Collection
    {
        return Note::with('group')
            ->where('user_id', $userId)
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get notes count for a user.
     */
    public function getNotesCount(int $userId): int
    {
        return Note::where('user_id', $userId)->count();
    }

    /**
     * Get groups count for a user.
     */
    public function getGroupsCount(int $userId): int
    {
        return Group::where('user_id', $userId)->count();
    }
}
