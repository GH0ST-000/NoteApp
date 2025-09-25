<?php

namespace App\Actions\Notes;

use App\Repositories\NoteRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Storage;

class DeleteNoteAction
{
    /**
     * @var NoteRepositoryInterface
     */
    protected $noteRepository;

    /**
     * DeleteNoteAction constructor.
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
    public function execute(int $noteId, int $userId): bool
    {
        // Check if the note belongs to the user
        if (! $this->noteRepository->belongsToUser($noteId, $userId)) {
            throw new AuthorizationException('Unauthorized action.');
        }

        // Get the note
        $note = $this->noteRepository->find($noteId);

        // Delete image if exists
        if ($note->image_path) {
            Storage::disk('public')->delete($note->image_path);
        }

        // Delete the note
        return $this->noteRepository->delete($noteId);
    }
}
