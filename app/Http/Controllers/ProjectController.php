<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            // Admin sees all projects with pagination
            $projects = Project::withCount(['tasks as to_do_tasks' => function ($query) {
                $query->where('status', 'to_do');
            }, 'tasks as in_progress_tasks' => function ($query) {
                $query->where('status', 'in_progress');
            }, 'tasks as completed_tasks' => function ($query) {
                $query->where('status', 'completed');
            }])->with('user', 'teamMembers')->paginate(9); // Changed to paginate
        } else {
            // Employee sees only assigned projects with pagination
            $projects = Auth::user()->assignedProjects()->withCount(['tasks as to_do_tasks' => function ($query) {
                $query->where('status', 'to_do');
            }, 'tasks as in_progress_tasks' => function ($query) {
                $query->where('status', 'in_progress');
            }, 'tasks as completed_tasks' => function ($query) {
                $query->where('status', 'completed');
            }])->with('user', 'teamMembers')->paginate(9); // Changed to paginate
        }

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        // Only admin can create projects
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $employees = User::where('role', 'employee')->get();
        return view('projects.create', compact('employees'));
    }

    public function store(Request $request)
    {
        // Only admin can create projects
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
            'assigned_employees' => 'nullable|array',
            'assigned_employees.*' => 'exists:users,id',
        ]);

        // Create project
        $project = Auth::user()->createdProjects()->create($request->all());

        // Assign employees to project
        if ($request->has('assigned_employees')) {
            $project->teamMembers()->attach($request->assigned_employees);
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        // Check if user has access to this project
        if (!Auth::user()->isAdmin() && !$project->teamMembers->contains(Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        $teamMembers = $project->teamMembers;
        $employees = User::where('role', 'employee')->get();

        return view('projects.show', compact('project', 'teamMembers', 'employees'));
    }

    public function edit(Project $project)
    {
        // Only admin can edit projects
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $employees = User::where('role', 'employee')->get();
        $assignedEmployees = $project->teamMembers->pluck('id')->toArray();

        return view('projects.edit', compact('project', 'employees', 'assignedEmployees'));
    }

    public function update(Request $request, Project $project)
    {
        // Only admin can update projects
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
            'assigned_employees' => 'nullable|array',
            'assigned_employees.*' => 'exists:users,id',
        ]);

        $project->update($request->all());

        // Sync assigned employees
        if ($request->has('assigned_employees')) {
            $project->teamMembers()->sync($request->assigned_employees);
        } else {
            $project->teamMembers()->detach();
        }

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // Only admin can delete projects
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $project->teamMembers()->detach();
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function addMember(Request $request, Project $project)
    {
        // Only admin can add members
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project->teamMembers()->attach($request->user_id);

        return redirect()->back()->with('success', 'Employee added to project successfully.');
    }

    public function removeMember(Request $request, Project $project)
    {
        // Only admin can remove members
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project->teamMembers()->detach($request->user_id);

        return redirect()->back()->with('success', 'Employee removed from project successfully.');
    }
}
