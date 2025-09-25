<?php

namespace App\Actions\Published;

use App\Models\Group;
use App\Models\Note;
use App\Repositories\GroupRepositoryInterface;
use App\Repositories\NoteRepositoryInterface;

class FindPublishedContentAction
{
    /**
     * @var NoteRepositoryInterface
     */
    protected $noteRepository;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * FindPublishedContentAction constructor.
     */
    public function __construct(
        NoteRepositoryInterface $noteRepository,
        GroupRepositoryInterface $groupRepository
    ) {
        $this->noteRepository = $noteRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Execute the action.
     */
    public function execute(string $slug): ?array
    {
        // Try to find a published note by slug
        $note = $this->noteRepository->findPublishedBySlug($slug);
        if ($note) {
            return [
                'type' => 'note',
                'data' => $note,
            ];
        }

        // Try to find a published group by slug
        $group = $this->groupRepository->findPublishedBySlug($slug);
        if ($group) {
            $publishedNotes = $this->noteRepository->getPublishedForGroup($group->id);

            return [
                'type' => 'group',
                'data' => [
                    'group' => $group,
                    'notes' => $publishedNotes,
                ],
            ];
        }

        // Neither note nor group found
        return null;
    }
}
