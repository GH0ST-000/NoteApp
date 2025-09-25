<?php

namespace App\Actions\Notes;

use App\DTOs\NoteDTO;
use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;
use Illuminate\Support\Str;

class CreateNoteAction
{
    /**
     * @var NoteRepositoryInterface
     */
    protected $noteRepository;

    /**
     * CreateNoteAction constructor.
     */
    public function __construct(NoteRepositoryInterface $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Execute the action.
     */
    public function execute(NoteDTO $noteDTO, int $userId): Note
    {
        // Handle image upload if present
        if ($noteDTO->imageFile) {
            $noteDTO->imagePath = $noteDTO->imageFile->store('notes', 'public');
        }

        // Generate slug if note is published
        if ($noteDTO->isPublished && ! $noteDTO->slug) {
            $noteDTO->slug = Str::slug($noteDTO->title).'-'.Str::random(8);
        }

        // Create the note
        return $this->noteRepository->createForUser($userId, $noteDTO->toArray());
    }
}
