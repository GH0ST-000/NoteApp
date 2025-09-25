<?php

namespace App\Http\Controllers;

use App\Actions\Groups\GetUserGroupsAction;
use App\Actions\Notes\CreateNoteAction;
use App\Actions\Notes\DeleteNoteAction;
use App\Actions\Notes\FindPublishedNoteAction;
use App\Actions\Notes\GetNoteAction;
use App\Actions\Notes\GetUserNotesAction;
use App\Actions\Notes\UpdateNoteAction;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * @var GetUserNotesAction
     */
    protected $getUserNotesAction;

    /**
     * @var GetUserGroupsAction
     */
    protected $getUserGroupsAction;

    /**
     * @var GetNoteAction
     */
    protected $getNoteAction;

    /**
     * @var CreateNoteAction
     */
    protected $createNoteAction;

    /**
     * @var UpdateNoteAction
     */
    protected $updateNoteAction;

    /**
     * @var DeleteNoteAction
     */
    protected $deleteNoteAction;

    /**
     * @var FindPublishedNoteAction
     */
    protected $findPublishedNoteAction;

    /**
     * NoteController constructor.
     */
    public function __construct(
        GetUserNotesAction $getUserNotesAction,
        GetUserGroupsAction $getUserGroupsAction,
        GetNoteAction $getNoteAction,
        CreateNoteAction $createNoteAction,
        UpdateNoteAction $updateNoteAction,
        DeleteNoteAction $deleteNoteAction,
        FindPublishedNoteAction $findPublishedNoteAction
    ) {
        $this->getUserNotesAction = $getUserNotesAction;
        $this->getUserGroupsAction = $getUserGroupsAction;
        $this->getNoteAction = $getNoteAction;
        $this->createNoteAction = $createNoteAction;
        $this->updateNoteAction = $updateNoteAction;
        $this->deleteNoteAction = $deleteNoteAction;
        $this->findPublishedNoteAction = $findPublishedNoteAction;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Get the authenticated user's notes, with pinned notes first
        $notes = $this->getUserNotesAction->execute(Auth::id());

        // Get the authenticated user's groups for the dropdown
        $groups = $this->getUserGroupsAction->execute(Auth::id());

        return view('notes.index', compact('notes', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Get the authenticated user's groups for the dropdown
        $groups = $this->getUserGroupsAction->execute(Auth::id());

        return view('notes.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        // Get the DTO from the request
        $noteDTO = $request->toDTO();

        // Execute the create note action
        $this->createNoteAction->execute($noteDTO, Auth::id());

        return redirect()->route('notes.index')
            ->with('success', 'Note created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        try {
            // Get the note and check authorization
            $note = $this->getNoteAction->execute($note->id, Auth::id());

            return view('notes.show', compact('note'));
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        try {
            // Get the note and check authorization
            $note = $this->getNoteAction->execute($note->id, Auth::id());

            // Get the authenticated user's groups for the dropdown
            $groups = $this->getUserGroupsAction->execute(Auth::id());

            return view('notes.edit', compact('note', 'groups'));
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        try {
            // Get the DTO from the request
            $noteDTO = $request->toDTO($note);

            // Execute the update note action
            $this->updateNoteAction->execute($note->id, $noteDTO, Auth::id());

            return redirect()->route('notes.index')
                ->with('success', 'Note updated successfully.');
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        try {
            // Execute the delete note action
            $this->deleteNoteAction->execute($note->id, Auth::id());

            return redirect()->route('notes.index')
                ->with('success', 'Note deleted successfully.');
        } catch (AuthorizationException $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * Display the published note.
     */
    public function showPublished(string $slug)
    {
        try {
            // Find the published note by slug
            $note = $this->findPublishedNoteAction->execute($slug);

            return view('notes.published', compact('note'));
        } catch (ModelNotFoundException $e) {
            abort(404, 'Published note not found.');
        }
    }
}
