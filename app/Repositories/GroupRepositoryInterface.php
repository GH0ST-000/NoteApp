<?php

namespace App\Repositories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

interface GroupRepositoryInterface extends RepositoryInterface
{
    /**
     * Get groups for a specific user.
     */
    public function getForUser(int $userId): Collection;

    /**
     * Get groups for a specific user with notes count.
     */
    public function getForUserWithNotesCount(int $userId): Collection;

    /**
     * Find a published group by slug.
     */
    public function findPublishedBySlug(string $slug): ?Group;

    /**
     * Find a group by ID with eager loaded notes.
     */
    public function findWithNotes(int $id): Group;

    /**
     * Create a group for a specific user.
     */
    public function createForUser(int $userId, array $attributes): Group;

    /**
     * Check if a group belongs to a user.
     */
    public function belongsToUser(int $groupId, int $userId): bool;
}
