<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TeamController extends Controller
{
    public function index(): View
    {
        $teams = auth()->user()->teams()
            ->withCount('members', 'projects')
            ->latest()
            ->paginate(10);

        return view('teams.index', compact('teams'));
    }

    public function create(): View
    {
        return view('teams.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'avatar' => 'nullable|image|max:1024'
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('team-avatars', 'public');
        }

        $team = auth()->user()->ownedTeams()->create($validated);
        $team->members()->attach(auth()->id(), ['role' => 'admin']);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    public function show(Team $team): View
    {
        $this->authorize('view', $team);
        
        $team->load(['members', 'projects' => function ($query) {
            $query->latest()->with('tasks');
        }]);

        return view('teams.show', compact('team'));
    }

    public function edit(Team $team): View
    {
        $this->authorize('update', $team);
        
        return view('teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'avatar' => 'nullable|image|max:1024'
        ]);

        if ($request->hasFile('avatar')) {
            if ($team->avatar) {
                Storage::disk('public')->delete($team->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('team-avatars', 'public');
        }

        $team->update($validated);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team): RedirectResponse
    {
        $this->authorize('delete', $team);

        if ($team->avatar) {
            Storage::disk('public')->delete($team->avatar);
        }

        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }

    public function addMember(Request $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:member,admin'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($team->members->contains($user)) {
            return back()->with('error', 'User is already a member of this team.');
        }

        $team->members()->attach($user, ['role' => $validated['role']]);

        return back()->with('success', 'Team member added successfully.');
    }

    public function removeMember(Request $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        $user = User::findOrFail($request->user_id);

        if ($team->owner_id === $user->id) {
            return back()->with('error', 'Cannot remove the team owner.');
        }

        $team->members()->detach($user);

        return back()->with('success', 'Team member removed successfully.');
    }

    public function updateMemberRole(Request $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,admin'
        ]);

        if ($team->owner_id === $validated['user_id']) {
            return back()->with('error', 'Cannot change the role of the team owner.');
        }

        $team->members()->updateExistingPivot($validated['user_id'], ['role' => $validated['role']]);

        return back()->with('success', 'Team member role updated successfully.');
    }
}
