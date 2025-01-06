<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = auth()->user()->projects()
            ->with(['team', 'tasks'])
            ->latest()
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $teams = auth()->user()->teams;
        return view('projects.create', compact('teams'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'team_id' => 'required|exists:teams,id',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high'
        ]);

        $project = auth()->user()->projects()->create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);
        
        $project->load(['team', 'tasks' => function ($query) {
            $query->latest();
        }, 'comments.user']);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);
        
        $teams = auth()->user()->teams;
        return view('projects.edit', compact('project', 'teams'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'team_id' => 'required|exists:teams,id',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high'
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
