<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    // Display all projects (admin) or assigned projects (user)
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $projects = Project::with(['teamMembers', 'user'])
                ->withCount([
                    'tasks as to_do_tasks' => fn($q) => $q->where('status', 'to_do'),
                    'tasks as in_progress_tasks' => fn($q) => $q->where('status', 'in_progress'),
                    'tasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
                ])
                ->latest()
                ->paginate(9);
        } else {
            // Get projects where user is team member
            $projects = Project::whereHas('teamMembers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->with(['teamMembers', 'user'])
                ->withCount([
                    'tasks as to_do_tasks' => fn($q) => $q->where('status', 'to_do'),
                    'tasks as in_progress_tasks' => fn($q) => $q->where('status', 'in_progress'),
                    'tasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
                ])
                ->latest()
                ->paginate(9);
        }

        return view('projects.index', compact('projects'));
    }

    // Show form to create project (admin only)
    public function create()
    {
        $employees = User::where('role', 'user')->get();
        return view('projects.create', compact('employees'));
    }

    public function edit(Project $project)
    {
        $employees = User::where('role', 'user')->get();
        $assignedEmployees = $project->teamMembers()->pluck('users.id')->toArray();

        return view('projects.edit', compact('project', 'employees', 'assignedEmployees'));
    }

    // Store a new project - WITH DEBUGGING
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $admin = Auth::user();

        // DEBUG
        Log::info('=== PROJECT CREATION START ===');
        Log::info('Request data:', $request->all());
        Log::info('Users to assign:', $request->users ?? []);

        // Create project
        $project = $admin->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'budget' => $request->budget,
        ]);

        Log::info("Project created with ID: {$project->id}");

        // Assign selected employees
        if ($request->has('users') && !empty($request->users)) {
            Log::info("Assigning users: " . implode(', ', $request->users));
            
            // Method 1: Using sync
            $result = $project->teamMembers()->sync($request->users);
            Log::info("Sync result:", $result);
            
            // Verify assignment
            $assignedCount = $project->teamMembers()->count();
            $assignedNames = $project->teamMembers->pluck('name')->join(', ');
            
            Log::info("Verified - Assigned {$assignedCount} users: {$assignedNames}");
            
            if ($assignedCount === 0) {
                Log::warning("SYNC MAY HAVE FAILED - Trying alternative method");
                
                // Method 2: Alternative using attach
                foreach ($request->users as $userId) {
                    $project->teamMembers()->attach($userId);
                    Log::info("Attached user ID: {$userId}");
                }
                
                $finalCount = $project->teamMembers()->count();
                Log::info("After attach - Final count: {$finalCount}");
            }
        } else {
            Log::info('No users selected for assignment');
        }

        Log::info('=== PROJECT CREATION COMPLETE ===');

        return redirect()->route('projects.index')
            ->with('success', 'Project created and employees assigned successfully.');
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $project->update($request->only(['name', 'description', 'start_date', 'end_date', 'status', 'budget']));

        // Sync team members
        if ($request->has('users')) {
            $project->teamMembers()->sync($request->users);
            Log::info("Updated assignments for project {$project->id}");
        } else {
            $project->teamMembers()->sync([]);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    // Show project details
    public function show(Project $project)
    {
        $user = Auth::user();

        // Admin can see all projects
        if ($user->isAdmin()) {
            $teamMembers = $project->teamMembers()->get();
            $employees = User::where('role', 'user')->get();
            return view('projects.show', compact('project', 'teamMembers', 'employees'));
        }

        // Normal user can see only assigned projects
        if (!$project->teamMembers()->where('user_id', $user->id)->exists()) {
            abort(403, 'Access denied. This project is not assigned to you.');
        }

        $teamMembers = $project->teamMembers()->get();
        return view('projects.show', compact('project', 'teamMembers'));
    }

    // Delete project
    public function destroy(Project $project)
    {
        $project->teamMembers()->detach();
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    // Add member to project
    public function addMember(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::find($request->project_id);
        $project->teamMembers()->syncWithoutDetaching([$request->user_id]);

        return redirect()->back()->with('success', 'Employee added successfully.');
    }

    // Remove member from project
    public function removeMember(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $project->teamMembers()->detach($request->user_id);

        return redirect()->back()->with('success', 'Employee removed successfully.');
    }
}