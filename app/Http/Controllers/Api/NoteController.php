<?php

namespace App\Http\Controllers\Api;

use App\Actions\Notes\CreateNoteAction;
use App\Actions\Notes\DeleteNoteAction;
use App\Actions\Notes\GetNoteAction;
use App\Actions\Notes\GetUserNotesAction;
use App\Actions\Notes\UpdateNoteAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    use ApiResponse;

    /**
     * @var GetUserNotesAction
     */
    protected $getUserNotesAction;

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
     * NoteController constructor.
     */
    public function __construct(
        GetUserNotesAction $getUserNotesAction,
        GetNoteAction $getNoteAction,
        CreateNoteAction $createNoteAction,
        UpdateNoteAction $updateNoteAction,
        DeleteNoteAction $deleteNoteAction
    ) {
        $this->getUserNotesAction = $getUserNotesAction;
        $this->getNoteAction = $getNoteAction;
        $this->createNoteAction = $createNoteAction;
        $this->updateNoteAction = $updateNoteAction;
        $this->deleteNoteAction = $deleteNoteAction;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $isPinned = $request->has('is_pinned') ? $request->boolean('is_pinned') : null;
        $notes = $this->getUserNotesAction->execute(Auth::id(), $isPinned);

        return $this->successResponse($notes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request): \Illuminate\Http\JsonResponse
    {
        // Get the DTO from the request
        $noteDTO = $request->toDTO();

        // Execute the create note action
        $note = $this->createNoteAction->execute($noteDTO, Auth::id());

        return $this->successMessageResponse('Note created successfully', $note, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $note = $this->getNoteAction->execute((int) $id, Auth::id());

            return $this->successResponse($note);
        } catch (AuthorizationException $e) {
            return $this->errorResponse($e->getMessage(), 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            // Get the note to update
            $note = $this->getNoteAction->execute((int) $id, Auth::id());

            // Get the DTO from the request
            $noteDTO = $request->toDTO($note);

            // Execute the update note action
            $note = $this->updateNoteAction->execute((int) $id, $noteDTO, Auth::id());

            return $this->successMessageResponse('Note updated successfully', $note);
        } catch (AuthorizationException $e) {
            return $this->errorResponse($e->getMessage(), 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            // Execute the delete note action
            $this->deleteNoteAction->execute((int) $id, Auth::id());

            return $this->successMessageResponse('Note deleted successfully');
        } catch (AuthorizationException $e) {
            return $this->errorResponse($e->getMessage(), 403);
        }
    }
}
