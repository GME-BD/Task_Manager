@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Enhanced Header Section -->
        <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-4 rounded mb-4 border-start border-5 border-primary">
            <div>
                <h2 class="mb-1 fw-bold text-dark">Upcoming Routines</h2>
                <p class="text-muted mb-0">Manage your daily, weekly, and monthly routines</p>
            </div>
            <a href="{{ route('routines.create') }}" class="btn btn-primary px-4 py-2 fw-semibold">
                <i class="bi bi-plus-circle me-2"></i>Add Routine
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Routines Dashboard -->
        <div class="row g-4">
            <!-- Daily Routines Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-day me-2 fs-5"></i>
                            <h4 class="mb-0">Daily Routines</h4>
                            <span class="badge bg-light text-primary ms-2">{{ count($upcomingDailyRoutines) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="kanban-column">
                            @forelse($upcomingDailyRoutines as $routine)
                                <div class="card mb-3 border-start border-3 border-success routine-card">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title fw-bold text-dark mb-0">{{ $routine->title }}</h6>
                                            <span class="badge bg-light text-dark small">
                                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }}
                                            </span>
                                        </div>
                                        <p class="card-text text-muted small mb-2">{{ $routine->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-calendar-week me-1"></i>
                                                {{ implode(', ', json_decode($routine->days, true) ?? []) }}
                                            </span>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('routines.edit', $routine->id) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this routine?');">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x text-muted fs-1"></i>
                                    <p class="text-muted mt-2">No upcoming daily routines</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('routines.showDaily') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View All Daily Routines
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Routines Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-info text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-week me-2 fs-5"></i>
                            <h4 class="mb-0">Weekly Routines</h4>
                            <span class="badge bg-light text-info ms-2">{{ count($upcomingWeeklyRoutines) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="kanban-column">
                            @forelse($upcomingWeeklyRoutines as $routine)
                                <div class="card mb-3 border-start border-3 border-info routine-card">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title fw-bold text-dark mb-0">{{ $routine->title }}</h6>
                                            <span class="badge bg-light text-dark small">
                                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }}
                                            </span>
                                        </div>
                                        <p class="card-text text-muted small mb-2">{{ $routine->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-calendar-range me-1"></i>
                                                {{ implode(', ', json_decode($routine->weeks, true) ?? []) }}
                                            </span>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('routines.edit', $routine->id) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this routine?');">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x text-muted fs-1"></i>
                                    <p class="text-muted mt-2">No upcoming weekly routines</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('routines.showWeekly') }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-eye me-1"></i>View All Weekly Routines
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Routines Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-warning text-dark py-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-month me-2 fs-5"></i>
                            <h4 class="mb-0">Monthly Routines</h4>
                            <span class="badge bg-light text-warning ms-2">{{ count($upcomingMonthlyRoutines) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="kanban-column">
                            @forelse($upcomingMonthlyRoutines as $routine)
                                <div class="card mb-3 border-start border-3 border-warning routine-card">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title fw-bold text-dark mb-0">{{ $routine->title }}</h6>
                                            <span class="badge bg-light text-dark small">
                                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }}
                                            </span>
                                        </div>
                                        <p class="card-text text-muted small mb-2">{{ $routine->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-calendar-month me-1"></i>
                                                {{ implode(
                                                    ', ',
                                                    array_map(function ($month) {
                                                        return DateTime::createFromFormat('!m', $month)->format('F');
                                                    }, json_decode($routine->months, true) ?? []),
                                                ) }}
                                            </span>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('routines.edit', $routine->id) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this routine?');">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x text-muted fs-1"></i>
                                    <p class="text-muted mt-2">No upcoming monthly routines</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('routines.showMonthly') }}" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-eye me-1"></i>View All Monthly Routines
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .routine-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .routine-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            border-bottom: none;
        }
        
        .kanban-column {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
        }
    </style>
@endsection