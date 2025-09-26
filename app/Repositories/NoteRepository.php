<?php

namespace App\Repositories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;

class NoteRepository extends BaseRepository implements NoteRepositoryInterface
{
    /**
     * NoteRepository constructor.
     */
    public function __construct(Note $model)
    {
        parent::__construct($model);
    }

    /**
     * Get notes for a specific user.
     */
    public function getForUser(int $userId, ?bool $isPinned = null): Collection
    {
        $query = $this->model->with('group')
            ->where('user_id', $userId)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc');

        if ($isPinned !== null) {
            $query->where('is_pinned', $isPinned);
        }

        return $query->get();
    }

    /**
     * Find a published note by slug.
     */
    public function findPublishedBySlug(string $slug): ?Note
    {
        return $this->model->with('group')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();
    }

    /**
     * Find a note by ID with group relationship.
     */
    public function findWithGroup(int $id): Note
    {
        return $this->model->with('group')->findOrFail($id);
    }

    /**
     * Create a note for a specific user.
     */
    public function createForUser(int $userId, array $attributes): Note
    {
        $attributes['user_id'] = $userId;

        return $this->create($attributes);
    }

    /**
     * Check if a note belongs to a user.
     */
    public function belongsToUser(int $noteId, int $userId): bool
    {
        return $this->model->where('id', $noteId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get published notes for a group.
     */
    public function getPublishedForGroup(int $groupId): Collection
    {
        return $this->model->with('group')
            ->where('group_id', $groupId)
            ->where('is_published', true)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();
    }
}
