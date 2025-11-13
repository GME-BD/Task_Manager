@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Enhanced Header -->
    <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-4 rounded mb-4 border-start border-5 border-primary">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Daily Routines</h2>
            <p class="text-muted mb-0">Manage your daily schedules and activities</p>
        </div>
        <div>
            <a href="{{ route('routines.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle me-2"></i>Add Routine
            </a>
            <a href="{{ route('routines.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to All
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ count($dailyRoutines) }}</h4>
                            <p class="mb-0">Total Routines</p>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $activeTodayCount ?? 0 }}</h4>
                            <p class="mb-0">Active Today</p>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $upcomingCount ?? 0 }}</h4>
                            <p class="mb-0">Upcoming Today</p>
                        </div>
                        <i class="bi bi-clock fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $completedCount ?? 0 }}</h4>
                            <p class="mb-0">Completed Today</p>
                        </div>
                        <i class="bi bi-check2-all fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search routines...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="dayFilter">
                        <option value="">All Days</option>
                        <option value="monday">Monday</option>
                        <option value="tuesday">Tuesday</option>
                        <option value="wednesday">Wednesday</option>
                        <option value="thursday">Thursday</option>
                        <option value="friday">Friday</option>
                        <option value="saturday">Saturday</option>
                        <option value="sunday">Sunday</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Routines Grid -->
    <div class="row" id="routinesContainer">
        @forelse($dailyRoutines as $routine)
            @php
                $days = json_decode($routine->days, true) ?? [];
                $currentDay = strtolower(now()->format('l'));
                $isActiveToday = in_array($currentDay, $days);
                $startTime = \Carbon\Carbon::parse($routine->start_time);
                $endTime = \Carbon\Carbon::parse($routine->end_time);
                $now = \Carbon\Carbon::now();
                $status = 'upcoming';
                
                if ($now->between($startTime, $endTime) && $isActiveToday) {
                    $status = 'active';
                } elseif ($now->gt($endTime) && $isActiveToday) {
                    $status = 'completed';
                }
            @endphp
            
            <div class="col-md-6 col-lg-4 mb-4 routine-card" 
                 data-days="{{ implode(',', $days) }}" 
                 data-status="{{ $status }}"
                 data-title="{{ strtolower($routine->title) }}"
                 data-description="{{ strtolower($routine->description) }}">
                <div class="card border-0 shadow-sm h-100 routine-item status-{{ $status }}">
                    <div class="card-header bg-transparent border-bottom-0 pt-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-{{ $status == 'active' ? 'success' : ($status == 'completed' ? 'warning' : 'info') }} mb-2">
                                    {{ ucfirst($status) }}
                                </span>
                                <h5 class="card-title fw-bold text-dark mb-1">{{ $routine->title }}</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary border-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('routines.edit', $routine->id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this routine?');">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @if($routine->description)
                            <p class="card-text text-muted mb-3">{{ $routine->description }}</p>
                        @endif
                        
                        <div class="routine-meta">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-week text-primary me-2"></i>
                                <span class="small">
                                    {{ implode(', ', array_map('ucfirst', $days)) }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock text-primary me-2"></i>
                                <span class="small">
                                    {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock-history text-primary me-2"></i>
                                <span class="small">
                                    {{ $startTime->diffInHours($endTime) }}h {{ $startTime->diffInMinutes($endTime) % 60 }}m
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if($isActiveToday)
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>Active today
                                @else
                                    <i class="bi bi-x-circle text-muted me-1"></i>Not today
                                @endif
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('routines.edit', $routine->id) }}" class="btn btn-outline-primary" title="Edit">
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
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar-x text-muted fs-1"></i>
                        <h4 class="text-muted mt-3">No Daily Routines Found</h4>
                        <p class="text-muted">Get started by creating your first daily routine</p>
                        <a href="{{ route('routines.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-2"></i>Create Your First Routine
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    {{-- @if($dailyRoutines->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $dailyRoutines->links() }}
        </div>
    @endif --}}
</div>

<style>
    .routine-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .routine-card:hover {
        transform: translateY(-5px);
    }
    
    .routine-item {
        border-left: 4px solid #4361ee;
    }
    
    .routine-item.status-active {
        border-left-color: #28a745;
    }
    
    .routine-item.status-completed {
        border-left-color: #ffc107;
    }
    
    .routine-item.status-upcoming {
        border-left-color: #17a2b8;
    }
    
    .routine-meta {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
    }
    
    .card-footer {
        background: transparent !important;
    }
    
    .dropdown-toggle::after {
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const dayFilter = document.getElementById('dayFilter');
        const statusFilter = document.getElementById('statusFilter');
        const routineCards = document.querySelectorAll('.routine-card');
        
        function filterRoutines() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedDay = dayFilter.value;
            const selectedStatus = statusFilter.value;
            
            routineCards.forEach(card => {
                const title = card.getAttribute('data-title');
                const description = card.getAttribute('data-description');
                const days = card.getAttribute('data-days').split(',');
                const status = card.getAttribute('data-status');
                
                const matchesSearch = !searchTerm || 
                    title.includes(searchTerm) || 
                    description.includes(searchTerm);
                    
                const matchesDay = !selectedDay || 
                    days.includes(selectedDay);
                    
                const matchesStatus = !selectedStatus || 
                    status === selectedStatus;
                
                if (matchesSearch && matchesDay && matchesStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterRoutines);
        dayFilter.addEventListener('change', filterRoutines);
        statusFilter.addEventListener('change', filterRoutines);
        
        // Initialize filter
        filterRoutines();
    });
</script>
@endsection