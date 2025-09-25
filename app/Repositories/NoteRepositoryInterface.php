<?php

namespace App\Repositories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;

interface NoteRepositoryInterface extends RepositoryInterface
{
    /**
     * Get notes for a specific user.
     */
    public function getForUser(int $userId, ?bool $isPinned = null): Collection;

    /**
     * Find a published note by slug.
     */
    public function findPublishedBySlug(string $slug): ?Note;

    /**
     * Create a note for a specific user.
     */
    public function createForUser(int $userId, array $attributes): Note;

    /**
     * Check if a note belongs to a user.
     */
    public function belongsToUser(int $noteId, int $userId): bool;

    /**
     * Get published notes for a group.
     */
    public function getPublishedForGroup(int $groupId): Collection;
}
