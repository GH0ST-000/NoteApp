<?php

namespace App\Actions\Notes;

use App\DTOs\NoteDTO;
use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateNoteAction
{
    /**
     * @var NoteRepositoryInterface
     */
    protected $noteRepository;

    /**
     * UpdateNoteAction constructor.
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
    public function execute(int $noteId, NoteDTO $noteDTO, int $userId): Note
    {
        // Check if the note belongs to the user
        if (! $this->noteRepository->belongsToUser($noteId, $userId)) {
            throw new AuthorizationException('Unauthorized action.');
        }

        // Get the note
        $note = $this->noteRepository->find($noteId);

        // Handle image upload if present
        if ($noteDTO->imageFile) {
            // Delete old image if exists
            if ($note->image_path) {
                Storage::disk('public')->delete($note->image_path);
            }

            $noteDTO->imagePath = $noteDTO->imageFile->store('notes', 'public');
        }

        // Update slug if publishing status changed
        if ($noteDTO->isPublished && ! $note->is_published) {
            // Note is being published
            $noteDTO->slug = Str::slug($noteDTO->title).'-'.Str::random(8);
        } elseif (! $noteDTO->isPublished && $note->is_published) {
            // Note is being unpublished
            $noteDTO->slug = null;
        }

        // Update the note
        return $this->noteRepository->update($noteId, $noteDTO->toArray());
    }
}
