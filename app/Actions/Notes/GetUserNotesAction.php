<?php

namespace App\Actions\Notes;

use App\Repositories\NoteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetUserNotesAction
{
    /**
     * @var NoteRepositoryInterface
     */
    protected $noteRepository;

    /**
     * GetUserNotesAction constructor.
     */
    public function __construct(NoteRepositoryInterface $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Execute the action.
     */
    public function execute(int $userId, ?bool $isPinned = null): Collection
    {
        return $this->noteRepository->getForUser($userId, $isPinned);
    }
}
