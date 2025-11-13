@extends('layouts.app')

@section('title', 'My Reminders')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">My Reminders</h2>
            <a href="{{ route('reminders.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Reminder
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Today's Reminders -->
        @if($todayReminders->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Today's Reminders</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($todayReminders as $reminder)
                            @include('reminders.partials.reminder-card', ['reminder' => $reminder])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Overdue Reminders -->
        @if($overdueReminders->count() > 0)
            <div class="card border-0 shadow-sm mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Overdue Reminders</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($overdueReminders as $reminder)
                            @include('reminders.partials.reminder-card', ['reminder' => $reminder])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Upcoming Reminders -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Upcoming Reminders</h5>
            </div>
            <div class="card-body">
                @if($upcomingReminders->count() > 0)
                    <div class="row">
                        @foreach($upcomingReminders as $reminder)
                            @include('reminders.partials.reminder-card', ['reminder' => $reminder])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-bell display-4 text-muted"></i>
                        <p class="text-muted mt-3">No upcoming reminders. <a href="{{ route('reminders.create') }}">Create one now!</a></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection