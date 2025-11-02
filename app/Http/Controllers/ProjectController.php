<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// class ProjectController extends Controller
// {
//     public function index()
//     {
//         $projects = Auth::user()->projects()->withCount(['tasks as to_do_tasks' => function ($query) {
//             $query->where('status', 'to_do');
//         }, 'tasks as in_progress_tasks' => function ($query) {
//             $query->where('status', 'in_progress');
//         }, 'tasks as completed_tasks' => function ($query) {
//             $query->where('status', 'completed');
//         }])->get();

//         return view('projects.index', compact('projects'));
//     }

//     public function create()
//     {
//         $users = User::all();
//         return view('projects.create', compact('users'));
//     }

//     // public function store(Request $request)
//     // {
//     //     $request->validate([
//     //         'name' => 'required|string|max:255',
//     //         'description' => 'nullable|string',
//     //         'start_date' => 'nullable|date',
//     //         'end_date' => 'nullable|date',
//     //         'status' => 'required|in:not_started,in_progress,completed',
//     //         'budget' => 'nullable|numeric',
//     //     ]);

//     //     Auth::isAdmin()->projects()->create($request->all());

//     //     return redirect()->route('projects.index')->with('success', 'Employee created successfully.');
//     // }


// public function store(Request $request)
// {
//     // Validate the request
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'start_date' => 'nullable|date',
//         'end_date' => 'nullable|date',
//         'status' => 'required|in:not_started,in_progress,completed',
//         'budget' => 'nullable|numeric',
//         'user' => 'nullable|array', // employee IDs
//         'user.*' => 'exists:users,id', // make sure IDs exist
//     ]);

//     // Get the authenticated admin user
//     $admin = Auth::user(); // ensure this user is admin

//     // Create the project associated with the admin
//     $project = $admin->projects()->create([
//         'name' => $request->name,
//         'description' => $request->description,
//         'start_date' => $request->start_date,
//         'end_date' => $request->end_date,
//         'status' => $request->status,
//         'budget' => $request->budget,
//     ]);

//     // Assign selected employees to the project
//     if ($request->has('user')) {
//         $project->users()->sync($request->user); // many-to-many relationship
//     }

//     return redirect()->route('projects.index')->with('success', 'Project created and employees assigned successfully.');
// }



//     public function show(Project $project)
//     {
//     //     $teamMembers = $project->users()->get();
//     //     $users = User::all();
//     //     return view('projects.show', compact('project', 'teamMembers', 'users'));
//     // }
//     // public function edit(Project $project)
//     // {
//     //     return view('projects.edit', compact('project'));
//     // }

//     // public function update(Request $request, Project $project)
//     // {
//     //     $request->validate([
//     //         'name' => 'required|string|max:255',
//     //         'description' => 'nullable|string',
//     //         'start_date' => 'nullable|date',
//     //         'end_date' => 'nullable|date',
//     //         'status' => 'required|in:not_started,in_progress,completed',
//     //         'budget' => 'nullable|numeric',
//     //     ]);

//     //     $project->update($request->all());

//     //     return redirect()->route('projects.index')->with('success', 'Employee updated successfully.');


   


//                 {
//                     $user = Auth::user();

//                     // Admin can see all projects
//                     if ($user->role === 'admin') {
//                         $teamMembers = $project->users()->get();
//                         $users = User::all();
//                         return view('projects.show', compact('project', 'teamMembers', 'users'));
//                     }

//                     // Normal user can see the project only if assigned
//                     if (!$project->users()->where('user_id', $user->id)->exists()) {
//                         abort(403, 'Access denied. This project is not assigned to you.');
//                     }

//                     $teamMembers = $project->users()->get();
//                     return view('projects.show', compact('project', 'teamMembers'));
//                 }





//     }

//     public function destroy(Project $project)
//     {
//         // FIX: The foreign key constraint error occurs because records exist in the
//         // `project_teams` pivot table. We must remove these child records first.
//         // We use `detach()` on the `teamProjects` relationship (the one used in `addMember`).
//         $project->teamProjects()->detach();

//         // If tasks are also linked to projects, and you haven't set `onDelete('cascade')`
//         // in your migration, you should also consider deleting/detaching them here.
//         // $project->tasks()->delete();

//         $project->delete();

//         return redirect()->route('projects.index')->with('success', 'Employee deleted successfully.');
//     }

//     public function addMember(Request $request)
//     {
//         $request->validate([
//             'project_id' => 'required|exists:projects,id',
//             'user_id' => 'required|exists:users,id',
//         ]);

//         $project = Project::find($request->project_id);
//         $project->teamProjects()->attach($request->user_id);
//         return redirect()->back()->with('success', 'Employee added successfully.');
//     }

// }



class ProjectController extends Controller
{
    // Show all projects for the current user
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin sees all projects
            $projects = Project::withCount([
                'tasks as to_do_tasks' => fn($q) => $q->where('status', 'to_do'),
                'tasks as in_progress_tasks' => fn($q) => $q->where('status', 'in_progress'),
                'tasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
            ])->get();
        } else {
            // Normal users see only assigned projects
            $projects = $user->assignedProjects()->withCount([
                'tasks as to_do_tasks' => fn($q) => $q->where('status', 'to_do'),
                'tasks as in_progress_tasks' => fn($q) => $q->where('status', 'in_progress'),
                'tasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
            ])->get();
        }

        return view('projects.index', compact('projects'));
    }

    // Show create form (Admin only)
    public function create()
    {
         $user = Auth::user();
            if ($user->role !== 'admin') {
        abort(403, 'Unauthorized');
    }
        $users = User::where('role', 'user')->get(); // only normal employees
        return view('projects.create', compact('users'));
    }

    // Store new project
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
            'user' => 'nullable|array', // employee IDs
            'user.*' => 'exists:users,id',
        ]);

        $admin = Auth::user();
        $project = $admin->projects()->create($request->only([
            'name','description','start_date','end_date','status','budget'
        ]));

        // Assign employees
        if ($request->filled('user')) {
            $project->users()->sync($request->user);
        }

        return redirect()->route('projects.index')->with('success', 'Project created and employees assigned successfully.');
    }

    // Show project details
    public function show(Project $project)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $teamMembers = $project->users()->get();
            $users = User::where('role', 'user')->get();
            return view('projects.show', compact('project', 'teamMembers', 'users'));
        }

        // Normal user can only view if assigned
        if (!$project->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'Access denied. This project is not assigned to you.');
        }

        $teamMembers = $project->users()->get();
        return view('projects.show', compact('project', 'teamMembers'));
    }

    // Edit project (Admin only)
    public function edit(Project $project)
    {
        $this->authorize('admin');
        return view('projects.edit', compact('project'));
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

        $project->update($request->only(['name','description','start_date','end_date','status','budget']));

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    // Delete project
    public function destroy(Project $project)
    {
        $project->teamProjects()->detach(); // remove assigned employees
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    // Add member to project
    public function addMember(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($request->project_id);
        $project->teamProjects()->syncWithoutDetaching($request->user_id);

        return redirect()->back()->with('success', 'Employee added successfully.');
    }
}
