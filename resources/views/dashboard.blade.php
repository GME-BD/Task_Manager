@extends('layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    {{-- Custom styles for a modern look without changing core HTML structure --}}
    <style>
        body {
            background-color: #f4f7f6; /* Light, modern background */
        }
        .card {
            border: none; /* Remove default border */
            border-radius: 12px; /* Smoother corners */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px); /* Subtle lift on hover */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important; /* Enhanced shadow */
        }
        .card-title {
            color: #34495e; /* Darker title color */
            font-weight: 600;
        }
        .btn-primary {
            background-color: #4CAF50; /* A fresh green primary color */
            border-color: #4CAF50;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .badge.bg-primary {
            background-color: #4CAF50 !important; /* Match badge to primary button */
        }
        .list-group-item {
            border-radius: 8px; /* Slightly rounded list items */
            margin-bottom: 5px;
            border-left: 5px solid transparent; /* Small left border for emphasis */
            background-color: #ffffff;
        }
        /* Custom list item backgrounds based on logic (assuming isToday, isPast methods are available) */
        .list-group-item.bg-warning {
            background-color: #ffe0b2 !important; /* Light orange for warning/today */
            border-left-color: #fb8c00;
        }
        .list-group-item.bg-danger {
            background-color: #ffcdd2 !important; /* Light red for danger/past */
            border-left-color: #e53935;
        }
        .list-group-item.bg-success {
            background-color: #c8e6c9 !important; /* Light green for success/future */
            border-left-color: #43a047;
        }
        .list-group-item .rounded-pill {
            font-weight: 500;
        }
    </style>

    <div class="container py-4">
        {{-- <h2 class="mb-4 text-center" style="color: #34495e; font-weight: 700;">Welcome to Dashboard</h2> --}}
        {{-- <p class="text-center mb-5 text-muted">A quick overview to manage your tasks, routines, notes, and files efficiently.</p> --}}
        
        <div class="row mb-4">
            {{-- Stat Cards --}}
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Tasks</h5>
                        <p class="card-text flex-grow-1 text-muted">You have <strong style="color: #4CAF50;">{{ $tasksCount }}</strong> tasks pending.</p>
                        <a href="{{ route('projects.index') }}" class="btn btn-primary mt-auto">View Tasks</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Routines</h5>
                        <p class="card-text flex-grow-1 text-muted">You have <strong style="color: #4CAF50;">{{ $routinesCount }}</strong> routines scheduled today.</p>
                        <a href="{{ route('routines.index') }}" class="btn btn-primary mt-auto">View Routines</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Notes</h5>
                        <p class="card-text flex-grow-1 text-muted">You have <strong style="color: #4CAF50;">{{ $notesCount }}</strong> notes saved.</p>
                        <a href="{{ route('notes.index') }}" class="btn btn-primary mt-auto">View Notes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Files</h5>
                        <p class="card-text flex-grow-1 text-muted">You have <strong style="color: #4CAF50;">{{ $filesCount }}</strong> files.</p>
                        <a href="{{ route('files.index') }}" class="btn btn-primary mt-auto">View Files</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            {{-- Recent/Today Lists --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Recent Tasks</h5>
                        <ul class="list-group flex-grow-1 list-group-flush"> {{-- Added list-group-flush for cleaner look --}}
                            @foreach($recentTasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $task->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $task->status == 'to_do' ? 'To Do' : 'In Progress' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Today's Routines</h5>
                        <ul class="list-group flex-grow-1 list-group-flush">
                            @foreach($todayRoutines as $routine)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $routine->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $routine->frequency }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Recent Notes</h5>
                        <ul class="list-group flex-grow-1 list-group-flush">
                            @foreach($recentNotes as $note)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $note->title }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Upcoming Reminders</h5>
                        <ul class="list-group flex-grow-1 list-group-flush">
                            @foreach($upcomingReminders as $reminder)
                                {{-- Note: The original Blade logic for conditional class assignment (bg-warning, bg-danger, bg-success) is preserved. The custom CSS above targets these classes for a modern palette. --}}
                                <li class="list-group-item d-flex justify-content-between align-items-center {{ $reminder->date->isToday() ? 'bg-warning' : ($reminder->date->isPast() ? 'bg-danger' : 'bg-success') }}">
                                    {{ $reminder->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $reminder->date->format('M d') }} {{ $reminder->time ? $reminder->time->format('H:i') : '' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection