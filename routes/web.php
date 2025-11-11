<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard - UPDATED with proper project assignment logic
    Route::get('/', function () {
        $user = Auth::user();

        // For admin, show all tasks. For employees, show only assigned tasks
        if ($user->isAdmin()) {
            $tasksCount = \App\Models\Task::count();
            $projectsCount = \App\Models\Project::count();
            $recentProjects = \App\Models\Project::with('teamMembers')->latest()->take(5)->get();
            $recentTasks = \App\Models\Task::latest()->take(5)->get();
        } else {
            // FIXED: Use assignedProjects() for employees
            $tasksCount = $user->tasks()->count();
            $projectsCount = $user->assignedProjects()->count();
            $recentProjects = $user->assignedProjects()->with('teamMembers')->latest()->take(5)->get();
            $recentTasks = $user->tasks()->latest()->take(5)->get();
        }

        $routinesCount = $user->routines()->count();
        $notesCount = $user->notes()->count();
        $remindersCount = $user->reminders()->count();
        $filesCount = $user->files()->count();

        $todayRoutines = $user->routines()->whereDate('start_time', now())->get();
        $recentNotes = $user->notes()->latest()->take(5)->get();
        $upcomingReminders = $user->reminders()->where('date', '>=', now())->orderBy('date')->take(5)->get();

        return view('dashboard', compact(
            'tasksCount',
            'projectsCount',
            'routinesCount',
            'notesCount',
            'remindersCount',
            'filesCount',
            'recentTasks',
            'recentProjects',
            'todayRoutines',
            'recentNotes',
            'upcomingReminders'
        ));
    })->name('dashboard');

    // Project Routes with Team Management - UPDATED
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');

        // Team Management Routes
        Route::post('/add-member', [ProjectController::class, 'addMember'])->name('add-member');
        Route::post('/{project}/remove-member', [ProjectController::class, 'removeMember'])->name('remove-member');

        // Project Tasks - Kanban Board
        Route::get('/{project}/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store'); // ADD THIS BACK
    });

    // Task Routes - UPDATED with create route and proper store
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/create', [TaskController::class, 'create'])->name('create'); // NEW
        Route::post('/', [TaskController::class, 'store'])->name('store'); // UPDATED - now handles project_id
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
        Route::post('/{task}/update-status', [TaskController::class, 'updateStatus'])->name('update-status');
    });

    // Routine Routes
    Route::prefix('routines')->name('routines.')->group(function () {
        Route::get('/', [RoutineController::class, 'index'])->name('index');
        Route::get('/create', [RoutineController::class, 'create'])->name('create');
        Route::post('/', [RoutineController::class, 'store'])->name('store');
        Route::get('/{routine}/edit', [RoutineController::class, 'edit'])->name('edit');
        Route::put('/{routine}', [RoutineController::class, 'update'])->name('update');
        Route::delete('/{routine}', [RoutineController::class, 'destroy'])->name('destroy');

        // Special Routine Views
        Route::get('/showAll', [RoutineController::class, 'showAll'])->name('showAll');
        Route::get('/daily', [RoutineController::class, 'showDaily'])->name('showDaily');
        Route::get('/weekly', [RoutineController::class, 'showWeekly'])->name('showWeekly');
        Route::get('/monthly', [RoutineController::class, 'showMonthly'])->name('showMonthly');
    });

    // File Routes
    Route::resource('files', FileController::class);

    // Note Routes
    Route::resource('notes', NoteController::class);

    // Reminder Routes
    Route::resource('reminders', ReminderController::class);

    // Checklist Item Routes
    // Route::prefix('checklist-items')->name('checklist-items.')->group(function () {
    //     Route::get('/', [ChecklistItemController::class, 'index'])->name('index');
    //     Route::post('/', [ChecklistItemController::class, 'store'])->name('store');
    //     Route::get('/{checklistItem}/edit', [ChecklistItemController::class, 'edit'])->name('edit');
    //     Route::put('/{checklistItem}', [ChecklistItemController::class, 'update'])->name('update');
    //     Route::delete('/{checklistItem}', [ChecklistItemController::class, 'destroy'])->name('destroy');
    //     Route::get('/{checklistItem}/update-status', [ChecklistItemController::class, 'updateStatus'])->name('update-status');
    // });

    // Checklist Item Routes - FIXED VERSION
    Route::post('/tasks/{task}/checklist-items', [ChecklistItemController::class, 'store'])->name('checklist-items.store');
    Route::post('/checklist-items/{checklistItem}/update-status', [ChecklistItemController::class, 'updateStatus'])->name('checklist-items.update-status');
    Route::delete('/checklist-items/{checklistItem}', [ChecklistItemController::class, 'destroy'])->name('checklist-items.destroy');

    // Mail Routes
    Route::prefix('mail')->name('mail.')->group(function () {
        Route::get('/', [MailController::class, 'index'])->name('inbox');
    });

    // DEBUG ROUTES - Remove after testing
    Route::get('/debug-assignments', function () {
        $user = Auth::user();

        echo "<h2>Debug: User Assignments</h2>";
        echo "User: {$user->name} (ID: {$user->id}, Role: {$user->role})<br><br>";

        if ($user->isAdmin()) {
            echo "<h3>All Projects:</h3>";
            $projects = \App\Models\Project::with('teamMembers')->get();
            foreach ($projects as $project) {
                echo "<strong>{$project->name}</strong> (ID: {$project->id})<br>";
                echo "Team Members: " . $project->teamMembers->count() . "<br>";
                foreach ($project->teamMembers as $member) {
                    echo " - {$member->name} (ID: {$member->id})<br>";
                }
                echo "<hr>";
            }
        } else {
            echo "<h3>Assigned Projects:</h3>";
            $assignedProjects = $user->assignedProjects()->with('teamMembers')->get();
            foreach ($assignedProjects as $project) {
                echo "<strong>{$project->name}</strong> (ID: {$project->id})<br>";
                echo "Team Members: " . $project->teamMembers->count() . "<br>";
                foreach ($project->teamMembers as $member) {
                    echo " - {$member->name} (ID: {$member->id})" . ($member->id == $user->id ? " <strong>[YOU]</strong>" : "") . "<br>";
                }
                echo "<hr>";
            }

            echo "<h3>Project Teams Table:</h3>";
            $teams = DB::table('project_teams')->get();
            foreach ($teams as $team) {
                echo "Project ID: {$team->project_id}, User ID: {$team->user_id}<br>";
            }
        }
    });

    Route::get('/debug-users', function () {
        $users = \App\Models\User::all();
        echo "<h2>All Users</h2>";
        foreach ($users as $user) {
            echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}<br>";
        }
    });
});
