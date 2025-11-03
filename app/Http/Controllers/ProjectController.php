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
        $projects = Auth::user()->projects()->withCount([
            'tasks as to_do_tasks' => fn($q) => $q->where('status', 'to_do'),
            'tasks as in_progress_tasks' => fn($q) => $q->where('status', 'in_progress'),
            'tasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
        ])->paginate(9);


        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $users = User::all();
        return view('projects.create', compact('users'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'start_date' => 'nullable|date',
    //         'end_date' => 'nullable|date',
    //         'status' => 'required|in:not_started,in_progress,completed',
    //         'budget' => 'nullable|numeric',
    //     ]);

    //     Auth::isAdmin()->projects()->create($request->all());

    //     return redirect()->route('projects.index')->with('success', 'Employee created successfully.');


    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
            'user' => 'nullable|array', // employee IDs
            'user.*' => 'exists:users,id', // make sure IDs exist
        ]);

        // Get the authenticated admin user
        $admin = Auth::user(); // ensure this user is admin

        // Create the project associated with the admin
        $project = $admin->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'budget' => $request->budget,
        ]);

        // Assign selected employees to the project
        if ($request->has('user')) {
            $project->users()->sync($request->user); // many-to-many relationship
        }

        return redirect()->route('projects.index')->with('success', 'Project created and employees assigned successfully.');
    }



    public function show(Project $project)
    {
        $teamMembers = $project->users()->get();
        $employees = User::all(); // or filter by role as you prefer
        return view('projects.show', compact('project', 'teamMembers', 'employees'));
    }

    public function edit(Project $project)
    {
        // All employees (or filter by role if needed)
        $employees = User::all();

        // Employees currently assigned to this project
        $assignedEmployees = $project->users()->pluck('users.id')->toArray();


        return view('projects.edit', compact('project', 'employees', 'assignedEmployees'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Employee updated successfully.'); {
            $user = Auth::user();

            // Admin can see all projects
            if ($user->role === 'admin') {
                $teamMembers = $project->users()->get();
                $users = User::all();
                return view('projects.show', compact('project', 'teamMembers', 'users'));
            }

            // Normal user can see the project only if assigned
            if (!$project->users()->where('user_id', $user->id)->exists()) {
                abort(403, 'Access denied. This project is not assigned to you.');
            }

            $teamMembers = $project->users()->get();
            return view('projects.show', compact('project', 'teamMembers'));
        }
    }

    public function destroy(Project $project)
    {
        // FIX: The foreign key constraint error occurs because records exist in the
        // `project_teams` pivot table. We must remove these child records first.
        // We use `detach()` on the `teamProjects` relationship (the one used in `addMember`).
        $project->teamProjects()->detach();

        // If tasks are also linked to projects, and you haven't set `onDelete('cascade')`
        // in your migration, you should also consider deleting/detaching them here.
        // $project->tasks()->delete();

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Employee deleted successfully.');
    }

    public function addMember(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::find($request->project_id);
        $project->teamProjects()->attach($request->user_id);
        return redirect()->back()->with('success', 'Employee added successfully.');
    }
}
