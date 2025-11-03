<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', function () {
        $user = Auth::user();

        //-------------------- For admin, show all tasks. For employees, show only assigned tasks-------------------------
        if ($user->isAdmin()) {
            $tasksCount = \App\Models\Task::count();
            $projectsCount = \App\Models\Project::count();
        } else {
            $tasksCount = $user->tasks()->count();
            $projectsCount = $user->assignedProjects()->count();
        }

        $routinesCount = $user->routines()->count();
        $notesCount = $user->notes()->count();
        $remindersCount = $user->reminders()->count();
        $filesCount = $user->files()->count();

        //-------------------------------------- Recent tasks based on role---------------------------------------------
        if ($user->isAdmin()) {
            $recentTasks = \App\Models\Task::latest()->take(5)->get();
        } else {
            $recentTasks = $user->tasks()->latest()->take(5)->get();
        }

        $todayRoutines = $user->routines()->whereDate('start_time', now())->get();
        $recentNotes = $user->notes()->latest()->take(5)->get();
        $upcomingReminders = $user->reminders()->where('date', '>=', now())->orderBy('date')->take(5)->get();

        //--------------------------- Recent projects based on role-----------------------------------------
        if ($user->isAdmin()) {
            $recentProjects = \App\Models\Project::latest()->take(5)->get();
        } else {
            $recentProjects = $user->assignedProjects()->latest()->take(5)->get();
        }

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

    //------------------------- Project Routes with Team Management-----------------------------------------
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');

        //------------------------------ Team Management Routes--------------------------------------------------------
        Route::post('/{project}/add-member', [ProjectController::class, 'addMember'])->name('add-member');
        Route::post('/{project}/remove-member', [ProjectController::class, 'removeMember'])->name('remove-member');

        //------------------------------ Project Tasks--------------------------------------------------------------
        Route::get('/{project}/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    });

    //------------------------------- Task Routes------------------------------------------------------
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::post('/{task}/update-status', [TaskController::class, 'updateStatus'])->name('update-status');
    });

    //------------------------------- Routine Routes--------------------------------------------------
    Route::prefix('routines')->name('routines.')->group(function () {
        Route::get('/', [RoutineController::class, 'index'])->name('index');
        Route::get('/create', [RoutineController::class, 'create'])->name('create');
        Route::post('/', [RoutineController::class, 'store'])->name('store');
        Route::get('/{routine}/edit', [RoutineController::class, 'edit'])->name('edit');
        Route::put('/{routine}', [RoutineController::class, 'update'])->name('update');
        Route::delete('/{routine}', [RoutineController::class, 'destroy'])->name('destroy');

        //--------------------------- Special Routine Views-------------------------------------------------
        Route::get('/showAll', [RoutineController::class, 'showAll'])->name('showAll');
        Route::get('/daily', [RoutineController::class, 'showDaily'])->name('showDaily');
        Route::get('/weekly', [RoutineController::class, 'showWeekly'])->name('showWeekly');
        Route::get('/monthly', [RoutineController::class, 'showMonthly'])->name('showMonthly');
    });

    //---------------------------------- File Routes--------------------------------------------------------
    Route::resource('files', FileController::class);

    //----------------------------------- Note Routes--------------------------------------------------------
    Route::resource('notes', NoteController::class);

    //----------------------------------- Reminder Routes------------------------------------------------------
    Route::resource('reminders', ReminderController::class);

    //------------------------------------- Checklist Item Routes-----------------------------------------------
    Route::prefix('checklist-items')->name('checklist-items.')->group(function () {
        Route::get('/', [ChecklistItemController::class, 'index'])->name('index');
        Route::post('/', [ChecklistItemController::class, 'store'])->name('store');
        Route::get('/{checklistItem}/edit', [ChecklistItemController::class, 'edit'])->name('edit');
        Route::put('/{checklistItem}', [ChecklistItemController::class, 'update'])->name('update');
        Route::delete('/{checklistItem}', [ChecklistItemController::class, 'destroy'])->name('destroy');
        Route::get('/{checklistItem}/update-status', [ChecklistItemController::class, 'updateStatus'])->name('update-status');
    });

    //------------------------------- Mail Routes------------------------------------------
    Route::prefix('mail')->name('mail.')->group(function () {
        Route::get('/', [MailController::class, 'index'])->name('inbox');
    });
});
