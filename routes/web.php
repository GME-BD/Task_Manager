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

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware([AdminMiddleware::class])->group(function () {


    // Display a listing of the resource (index)
Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');

    // Show the form for creating a new resource
Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');

// Store a newly created resource in storage
Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');

// Display the specified resource (show)
Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// Show the form for editing the specified resource
Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');

// Update the specified resource in storage
Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

// Delete the specified resource from storage
Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

});


Route::middleware(['auth'])->group(function () {
    Route::controller(MailController::class)->prefix('mail')->name('mail.')->group(function () {
        Route::get('/', 'index')->name('inbox');
    });
    
//    Route::resource('projects', ProjectController::class);


Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');











     Route::post('project/team', [ProjectController::class, 'addMember'])->name('projects.addMember');
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');

    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('tasks/{task}/update-status', [TaskController::class, 'updateStatus']);

    Route::resource('routines', RoutineController::class)->except(['show']);
    Route::get('routines/showAll', [RoutineController::class, 'showAll'])->name('routines.showAll');
    Route::get('routines/daily', [RoutineController::class, 'showDaily'])->name('routines.showDaily');
    Route::get('routines/weekly', [RoutineController::class, 'showWeekly'])->name('routines.showWeekly');
    Route::get('routines/monthly', [RoutineController::class, 'showMonthly'])->name('routines.showMonthly');
    Route::resource('files', FileController::class);
    Route::resource('notes', NoteController::class);
    Route::resource('reminders', ReminderController::class);
    Route::resource('checklist-items', ChecklistItemController::class);
    Route::get('checklist-items/{checklistItem}/update-status', [ChecklistItemController::class, 'updateStatus'])->name('checklist-items.update-status');
    Route::get('/', function () {
        $user = Auth::user();
        $tasksCount = $user->tasks()->count();
        $routinesCount = $user->routines()->count();
        $notesCount = $user->notes()->count();
        $remindersCount = $user->reminders()->count();
        $filesCount = $user->files()->count();
        $recentTasks = $user->tasks()->latest()->take(5)->get();
        $todayRoutines = $user->routines()->whereDate('start_time', now())->get();
        $recentNotes = $user->notes()->latest()->take(5)->get();

        $upcomingReminders = $user->reminders()->where('date', '>=', now())->orderBy('date')->take(5)->get();

        return view('dashboard', compact(
            'tasksCount',
            'routinesCount',
            'notesCount',
            'remindersCount',
            'filesCount',
            'recentTasks',
            'todayRoutines',
            'recentNotes',
            'upcomingReminders'
        ));
    })->name('dashboard');
        
    });




