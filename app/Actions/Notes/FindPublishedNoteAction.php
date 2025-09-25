<?php

namespace App\Actions\Notes;

use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FindPublishedNoteAction
{
    /**
     * @var NoteRepositoryInterface
     */
    protected $noteRepository;

    /**
     * FindPublishedNoteAction constructor.
     */
    public function __construct(NoteRepositoryInterface $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Execute the action.
     *
     * @throws ModelNotFoundException
     */
    public function execute(string $slug): Note
    {
        $note = $this->noteRepository->findPublishedBySlug($slug);

        if (! $note) {
            throw new ModelNotFoundException('Published note not found.');
        }

        return $note;
    }
}
