@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Enhanced Header -->
    <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-4 rounded mb-4 border-start border-5 border-info">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Weekly Routines</h2>
            <p class="text-muted mb-0">Manage your weekly schedules and activities</p>
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
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ count($weeklyRoutines) }}</h4>
                            <p class="mb-0">Total Routines</p>
                        </div>
                        <i class="bi bi-calendar-week fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $activeThisWeekCount ?? 0 }}</h4>
                            <p class="mb-0">Active This Week</p>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $upcomingCount ?? 0 }}</h4>
                            <p class="mb-0">Upcoming</p>
                        </div>
                        <i class="bi bi-clock fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $completedCount ?? 0 }}</h4>
                            <p class="mb-0">Completed</p>
                        </div>
                        <i class="bi bi-check2-all fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Week Indicator -->
    <div class="card border-0 shadow-sm mb-4 bg-light">
        <div class="card-body text-center">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-1">Current Week: <span class="text-primary">Week {{ $currentWeek ?? now()->weekOfYear }}</span></h5>
                    <small class="text-muted">{{ now()->startOfWeek()->format('M j') }} - {{ now()->endOfWeek()->format('M j, Y') }}</small>
                </div>
                <div class="col-md-6">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info" id="prevWeek">
                            <i class="bi bi-chevron-left"></i> Previous
                        </button>
                        <button class="btn btn-outline-info" id="currentWeek">
                            Current Week
                        </button>
                        <button class="btn btn-outline-info" id="nextWeek">
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search weekly routines...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="weekFilter">
                        <option value="">All Weeks</option>
                        @for($i = 1; $i <= 52; $i++)
                            <option value="{{ $i }}" {{ $i == ($currentWeek ?? now()->weekOfYear) ? 'selected' : '' }}>
                                Week {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" id="clearFilters">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Routines Grid -->
    <div class="row" id="routinesContainer">
        @forelse($weeklyRoutines as $routine)
            @php
                $weeks = json_decode($routine->weeks, true) ?? [];
                $currentWeek = now()->weekOfYear;
                $isActiveThisWeek = in_array($currentWeek, $weeks);
                $startTime = \Carbon\Carbon::parse($routine->start_time);
                $endTime = \Carbon\Carbon::parse($routine->end_time);
                $now = \Carbon\Carbon::now();
                
                // Determine status
                $status = 'upcoming';
                if ($isActiveThisWeek) {
                    if ($now->between($startTime, $endTime)) {
                        $status = 'active';
                    } elseif ($now->gt($endTime)) {
                        $status = 'completed';
                    }
                }
                
                // Format weeks for display
                $weekDisplay = array_map(function($week) {
                    return "Week " . $week;
                }, $weeks);
            @endphp
            
            <div class="col-md-6 col-lg-4 mb-4 routine-card" 
                 data-weeks="{{ implode(',', $weeks) }}" 
                 data-status="{{ $status }}"
                 data-title="{{ strtolower($routine->title) }}"
                 data-description="{{ strtolower($routine->description) }}">
                <div class="card border-0 shadow-sm h-100 routine-item status-{{ $status }}">
                    <div class="card-header bg-transparent border-bottom-0 pt-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-{{ $status == 'active' ? 'success' : ($status == 'completed' ? 'secondary' : 'warning') }} mb-2">
                                    @if($status == 'active')
                                        <i class="bi bi-play-circle me-1"></i>Active Now
                                    @else
                                        {{ ucfirst($status) }}
                                    @endif
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
                                <i class="bi bi-calendar-range text-info me-2"></i>
                                <span class="small">
                                    {{ implode(', ', $weekDisplay) }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock text-info me-2"></i>
                                <span class="small">
                                    {{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock-history text-info me-2"></i>
                                <span class="small">
                                    {{ $startTime->diffInHours($endTime) }}h {{ $startTime->diffInMinutes($endTime) % 60 }}m
                                </span>
                            </div>
                        </div>
                        
                        <!-- Week Indicators -->
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Active Weeks:</small>
                                <small class="text-muted">{{ count($weeks) }} weeks</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                @php
                                    $activeWeeksCount = count(array_intersect($weeks, range(max(1, $currentWeek - 2), min(52, $currentWeek + 2))));
                                    $progress = min(100, ($activeWeeksCount / 5) * 100);
                                @endphp
                                <div class="progress-bar bg-info" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if($isActiveThisWeek)
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>Active this week
                                @else
                                    <i class="bi bi-x-circle text-muted me-1"></i>Not this week
                                @endif
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('routines.edit', $routine->id) }}" class="btn btn-outline-info" title="Edit">
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
                        <h4 class="text-muted mt-3">No Weekly Routines Found</h4>
                        <p class="text-muted">Get started by creating your first weekly routine</p>
                        <a href="{{ route('routines.create') }}" class="btn btn-info mt-2">
                            <i class="bi bi-plus-circle me-2"></i>Create Weekly Routine
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    {{-- @if($weeklyRoutines->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $weeklyRoutines->links() }}
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
        border-left: 4px solid #17a2b8;
    }
    
    .routine-item.status-active {
        border-left-color: #28a745;
    }
    
    .routine-item.status-completed {
        border-left-color: #6c757d;
    }
    
    .routine-item.status-upcoming {
        border-left-color: #ffc107;
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
    
    .bg-info {
        background: linear-gradient(135deg, #17a2b8, #138496) !important;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 3px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const weekFilter = document.getElementById('weekFilter');
        const statusFilter = document.getElementById('statusFilter');
        const clearFilters = document.getElementById('clearFilters');
        const routineCards = document.querySelectorAll('.routine-card');
        
        function filterRoutines() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedWeek = weekFilter.value;
            const selectedStatus = statusFilter.value;
            
            routineCards.forEach(card => {
                const title = card.getAttribute('data-title');
                const description = card.getAttribute('data-description');
                const weeks = card.getAttribute('data-weeks').split(',');
                const status = card.getAttribute('data-status');
                
                const matchesSearch = !searchTerm || 
                    title.includes(searchTerm) || 
                    description.includes(searchTerm);
                    
                const matchesWeek = !selectedWeek || 
                    weeks.includes(selectedWeek);
                    
                const matchesStatus = !selectedStatus || 
                    status === selectedStatus;
                
                if (matchesSearch && matchesWeek && matchesStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        function clearAllFilters() {
            searchInput.value = '';
            weekFilter.value = '';
            statusFilter.value = '';
            filterRoutines();
        }
        
        searchInput.addEventListener('input', filterRoutines);
        weekFilter.addEventListener('change', filterRoutines);
        statusFilter.addEventListener('change', filterRoutines);
        clearFilters.addEventListener('click', clearAllFilters);
        
        // Week navigation (placeholder functionality)
        document.getElementById('prevWeek')?.addEventListener('click', function() {
            alert('Previous week navigation would be implemented here');
        });
        
        document.getElementById('currentWeek')?.addEventListener('click', function() {
            weekFilter.value = '{{ $currentWeek ?? now()->weekOfYear }}';
            filterRoutines();
        });
        
        document.getElementById('nextWeek')?.addEventListener('click', function() {
            alert('Next week navigation would be implemented here');
        });
        
        // Initialize filter
        filterRoutines();
    });
</script>
@endsection