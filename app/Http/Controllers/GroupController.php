<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Repositories\GroupRepositoryInterface;
use App\Repositories\NoteRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var NoteRepositoryInterface
     */
    protected $noteRepository;

    /**
     * GroupController constructor.
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        NoteRepositoryInterface $noteRepository
    ) {
        $this->groupRepository = $groupRepository;
        $this->noteRepository = $noteRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user's groups with notes count
        $groups = $this->groupRepository->getForUserWithNotesCount(Auth::id());

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        // Generate slug if group is published
        $slug = null;
        if ($request->input('is_published', false)) {
            $slug = Str::slug($request->input('name')).'-'.Str::random(8);
        }

        // Create the group
        $this->groupRepository->createForUser(Auth::id(), [
            'name' => $request->input('name'),
            'is_published' => $request->input('is_published', false),
            'slug' => $slug,
        ]);

        return redirect()->route('groups.index')
            ->with('success', 'Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        // Check if the group belongs to the authenticated user
        if (! $this->groupRepository->belongsToUser($group->id, Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        // Get the group with eager loaded notes
        $group = $this->groupRepository->findWithNotes($group->id);

        // Get the notes in this group
        $notes = $group->notes->sortByDesc('is_pinned')->sortByDesc('updated_at');

        return view('groups.show', compact('group', 'notes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        // Check if the group belongs to the authenticated user
        if (! $this->groupRepository->belongsToUser($group->id, Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        // Check if the group belongs to the authenticated user
        if (! $this->groupRepository->belongsToUser($group->id, Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        $attributes = [
            'name' => $request->input('name'),
            'is_published' => $request->input('is_published', false),
        ];

        // Update slug if publishing status changed
        if ($request->input('is_published', false) && ! $group->is_published) {
            // Group is being published
            $attributes['slug'] = Str::slug($request->input('name')).'-'.Str::random(8);
        } elseif (! $request->input('is_published', false) && $group->is_published) {
            // Group is being unpublished
            $attributes['slug'] = null;
        }

        // Update the group
        $this->groupRepository->update($group->id, $attributes);

        return redirect()->route('groups.index')
            ->with('success', 'Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        // Check if the group belongs to the authenticated user
        if (! $this->groupRepository->belongsToUser($group->id, Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the group (notes will remain but will be unassigned from this group)
        $this->groupRepository->delete($group->id);

        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully.');
    }

    /**
     * Display the published group.
     */
    public function showPublished(string $slug)
    {
        // Find the published group by slug with eager loaded notes
        $group = $this->groupRepository->findPublishedBySlug($slug);

        if (! $group) {
            abort(404, 'Published group not found.');
        }

        // Get the published notes in this group
        $notes = $group->notes->where('is_published', true)->sortByDesc('is_pinned')->sortByDesc('updated_at');

        return view('groups.published', compact('group', 'notes'));
    }
}
