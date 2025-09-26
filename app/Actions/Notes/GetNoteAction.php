<?php

namespace App\Actions\Notes;

use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

class GetNoteAction
{
    /**
     * @var NoteRepositoryInterface
     */
    protected $noteRepository;

    /**
     * GetNoteAction constructor.
     */
    public function __construct(NoteRepositoryInterface $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Execute the action.
     *
     * @throws AuthorizationException
     */
    public function execute(int $noteId, int $userId): Note
    {
        // Check if the note belongs to the user
        if (! $this->noteRepository->belongsToUser($noteId, $userId)) {
            throw new AuthorizationException('Unauthorized action.');
        }

        // Get the note with group relationship eager loaded
        return $this->noteRepository->findWithGroup($noteId);
    }
}
