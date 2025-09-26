<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface DashboardRepositoryInterface
{
    /**
     * Get user with eager loaded relationships for dashboard.
     */
    public function getUserWithRelationships(int $userId): User;

    /**
     * Get recent notes for a user with group relationship.
     */
    public function getRecentNotes(int $userId, int $limit = 5): Collection;

    /**
     * Get notes count for a user.
     */
    public function getNotesCount(int $userId): int;

    /**
     * Get groups count for a user.
     */
    public function getGroupsCount(int $userId): int;
}
