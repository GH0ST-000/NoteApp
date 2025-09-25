<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user's groups
        $groups = Auth::user()->groups()
            ->orderBy('name')
            ->get();

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
        $group = Auth::user()->groups()->create([
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
        if ($group->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get the notes in this group
        $notes = $group->notes()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('groups.show', compact('group', 'notes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        // Check if the group belongs to the authenticated user
        if ($group->user_id !== Auth::id()) {
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
        if ($group->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        // Update slug if publishing status changed
        if ($request->input('is_published', false) && ! $group->is_published) {
            // Group is being published
            $group->slug = Str::slug($request->input('name')).'-'.Str::random(8);
        } elseif (! $request->input('is_published', false) && $group->is_published) {
            // Group is being unpublished
            $group->slug = null;
        }

        // Update the group
        $group->update([
            'name' => $request->input('name'),
            'is_published' => $request->input('is_published', false),
        ]);

        return redirect()->route('groups.index')
            ->with('success', 'Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        // Check if the group belongs to the authenticated user
        if ($group->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the group (notes will remain but will be unassigned from this group)
        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully.');
    }

    /**
     * Display the published group.
     */
    public function showPublished(string $slug)
    {
        // Find the published group by slug
        $group = Group::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Get the published notes in this group
        $notes = $group->notes()
            ->where('is_published', true)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('groups.published', compact('group', 'notes'));
    }
}
