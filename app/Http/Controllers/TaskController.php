<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        // Get all tasks grouped by status
        $tasks = $project->tasks()->get()->groupBy('status');

        // Ensure all status keys exist with empty collections
        $tasks = [
            'to_do' => $tasks['to_do'] ?? collect(),
            'in_progress' => $tasks['in_progress'] ?? collect(),
            'completed' => $tasks['completed'] ?? collect(),
        ];

        $users = $project->teamMembers()->get();
        return view('tasks.index', compact('project', 'tasks', 'users'));
    }

    // NEW: Show form to create task
    public function create()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $projects = Project::with('teamMembers')->get();
        } else {
            // Employees can only see projects they're assigned to
            $projects = $user->assignedProjects()->with('teamMembers')->get();
        }

        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request, Project $project = null)
    {
        // If project is provided via route parameter (from projects.tasks.store)
        if ($project) {
            $request->merge(['project_id' => $project->id]);
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to_do,in_progress,completed',
        ]);

        // Get the project
        $project = Project::findOrFail($request->project_id);

        // Check if user is authorized to create tasks in this project
        $user = Auth::user();
        if (!$user->isAdmin() && !$project->teamMembers()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'You are not authorized to create tasks in this project.');
        }

        // Check if assigned user is a team member of the project
        if (!$project->teamMembers()->where('user_id', $request->user_id)->exists()) {
            return redirect()->back()->with('error', 'The selected user is not a team member of this project.');
        }

        // Create the task
        $task = $project->tasks()->create($request->all());

        // Redirect based on where the request came from
        if ($request->has('from_kanban')) {
            return redirect()->route('projects.tasks.index', $project)->with('success', 'Task created successfully.');
        }

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Task created successfully.');
    }

    // NEW: Show form to edit task
    public function edit(Task $task)
    {
        $user = Auth::user();

        // Authorization check - user must be admin or assigned to the task's project
        if (!$user->isAdmin() && !$task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not authorized to edit this task.');
        }

        return view('tasks.edit', compact('task'));
    }

    public function show(Task $task)
    {
        // Authorization check - user must be admin or assigned to the task's project
        $user = Auth::user();
        if (!$user->isAdmin() && !$task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not authorized to view this task.');
        }

        return view('tasks.show', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to_do,in_progress,completed',
        ]);

        // Authorization check
        $user = Auth::user();
        if (!$user->isAdmin() && !$task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not authorized to update this task.');
        }

        $task->update($request->all());

        return redirect()->route('projects.tasks.index', $task->project_id)->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:to_do,in_progress,completed',
        ]);

        // Authorization check
        $user = Auth::user();
        if (!$user->isAdmin() && !$task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update(['status' => $request->status]);

        return response()->json(['message' => 'Task status updated successfully.']);
    }

    // NEW: Delete task method
    public function destroy(Task $task)
    {
        // Authorization check
        $user = Auth::user();
        if (!$user->isAdmin() && !$task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not authorized to delete this task.');
        }

        $projectId = $task->project_id;
        $task->delete();

        return redirect()->route('projects.tasks.index', $projectId)->with('success', 'Task deleted successfully.');
    }
}
