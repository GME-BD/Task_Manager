@extends('layouts.app')

@section('title', 'My Reminders')

@section('content')
    <div class="container-fluid px-4 py-3">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-light shadow-sm p-3 rounded-3 mb-4 border-start border-4 border-primary">
            <h2 class="fw-bold text-primary mb-0">
                <i class="bi bi-bell-fill me-2"></i> My Reminders
            </h2>
            <a href="{{ route('reminders.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Reminder
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- TODAY'S REMINDERS -->
        @if ($todayReminders->count() > 0)
            <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white fw-semibold d-flex align-items-center">
                    <i class="bi bi-calendar-day me-2"></i> Today's Reminders
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        @foreach ($todayReminders as $reminder)
                            @include('reminders.partials.reminder-card', ['reminder' => $reminder])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- OVERDUE REMINDERS -->
        @if ($overdueReminders->count() > 0)
            <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-danger text-white fw-semibold d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle me-2"></i> Overdue Reminders
                </div>
                <div class="card-body bg-light">
                    <div class="row g-3">
                        @foreach ($overdueReminders as $reminder)
                            @include('reminders.partials.reminder-card', ['reminder' => $reminder])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- UPCOMING REMINDERS -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-success text-white fw-semibold d-flex align-items-center">
                <i class="bi bi-calendar-week me-2"></i> Upcoming Reminders
            </div>
            <div class="card-body bg-light">
                @if ($upcomingReminders->count() > 0)
                    <div class="row g-3">
                        @foreach ($upcomingReminders as $reminder)
                            @include('reminders.partials.reminder-card', ['reminder' => $reminder])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash display-4 text-muted"></i>
                        <p class="text-muted mt-3 fs-5">
                            No upcoming reminders. <br>
                            <a href="{{ route('reminders.create') }}" class="fw-semibold text-primary text-decoration-none">
                                Create one now!
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Hover effect for reminder cards -->
    <style>
        .hover-lift {
            transition: all 0.25s ease-in-out;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            font-size: 1.1rem;
        }
    </style>
@endsection
