@extends('layouts.app')

@section('title')
    Projects
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    :root {
        --primary-color: #007bff;
        --primary-dark: #0056b3;
        --accent-gradient: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);
        --card-border-radius: 1rem;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .page-header {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .header-title {
        font-weight: 600;
        color: #343a40;
    }

    .card {
        border: none;
        border-radius: var(--card-border-radius);
        box-shadow: var(--card-shadow);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.35rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--primary-dark);
    }

    .status-badge {
        font-weight: 600;
        padding: 0.35em 0.7em;
        border-radius: 0.5rem;
        font-size: 0.85rem;
    }

    .status-not_started { background-color: #6c757d; color: white; }
    .status-in_progress { background-color: #17a2b8; color: white; }
    .status-completed { background-color: #28a745; color: white; }
    .status-pending { background-color: #ffc107; color: #343a40; }

    .task-stats {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin: 1rem 0;
    }

    .task-stat-item { font-size: 0.85rem; color: #6c757d; }
    .task-stat-number { font-weight: 600; color: var(--primary-dark); }

    .action-group .btn { 
        border-radius: 0.5rem; 
        padding: 0.5rem 0.75rem; 
        font-size: 0.9rem; 
        margin-right: 0.25rem; 
        transition: all 0.2s ease;
    }
    
    .action-group .btn:hover {
        transform: translateY(-2px);
    }
    
    .btn-accent { 
        background: var(--accent-gradient); 
        border: none; 
        font-weight: 500; 
        padding: 0.5rem 1.5rem; 
        border-radius: 0.75rem; 
        color: white; 
        transition: opacity 0.3s ease; 
    }
    
    .btn-accent:hover { 
        opacity: 0.9; 
        color: white; 
        transform: translateY(-2px);
    }

    .deadline-text { font-size: 0.9rem; color: #6c757d; }
    .assigned-members { margin-top: 0.5rem; }
    .member-badge { 
        background: #e9ecef; 
        border-radius: 1rem; 
        padding: 0.25rem 0.5rem; 
        font-size: 0.75rem; 
        color: #495057; 
        display: inline-block;
        margin: 0.1rem;
    }
    
    .creator-info { font-size: 0.8rem; color: #6c757d; margin-bottom: 0.5rem; }
    .assignment-info { font-size: 0.8rem; color: #28a745; margin-bottom: 0.5rem; font-weight: 500; }
    .pagination { margin-top: 2rem; }
    .page-link { border-radius: 0.5rem; margin: 0 0.25rem; border: 1px solid #dee2e6; }
    .page-item.active .page-link { background-color: var(--primary-color); border-color: var(--primary-color); }
    
    .empty-state {
        background: white;
        border-radius: 1rem;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: var(--card-shadow);
    }
    
    .alert-light {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
    }
    
    .assigned-to-me-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="page-header d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="header-title mb-1">
                <i class="bi bi-folder me-2"></i>
                {{ Auth::user()->isAdmin() ? 'All Projects' : 'My Projects' }}
            </h2>
            <p class="text-muted mb-0">
                {{ Auth::user()->isAdmin() ? 'Manage all projects and assign them to team members' : 'Projects assigned to you' }}
            </p>
        </div>

        @if(Auth::user()->isAdmin())
        <a href="{{ route('projects.create') }}" class="btn btn-accent shadow">
            <i class="bi bi-plus-circle me-1"></i> Create Project
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-light border-0" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                Showing <strong>{{ $projects->count() }}</strong>
                @if($projects->total() > $projects->count())
                    of <strong>{{ $projects->total() }}</strong>
                @endif
                project(s)
                
                @if($projects->total() > 0)
                    <span class="ms-2">
                        <i class="bi bi-clock-history me-1"></i>
                        Last updated: {{ now()->format('M j, Y g:i A') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($projects as $project)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card project-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $project->name }}</h5>
                            <div class="d-flex flex-column align-items-end">
                                @php
                                    $statusClass = [
                                        'not_started' => 'status-not_started',
                                        'in_progress' => 'status-in_progress',
                                        'completed' => 'status-completed',
                                    ][$project->status] ?? 'status-pending';
                                    
                                    $statusText = [
                                        'not_started' => 'Not Started',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                    ][$project->status] ?? 'Unknown';
                                @endphp
                                <span class="status-badge {{ $statusClass }} mb-1">{{ $statusText }}</span>
                                
                                {{-- Show "Assigned to me" badge for employees --}}
                                @if(!Auth::user()->isAdmin() && $project->teamMembers->contains(Auth::id()))
                                    <span class="assigned-to-me-badge">
                                        <i class="bi bi-person-check me-1"></i>Assigned to me
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Show creator info for admin --}}
                        @if(Auth::user()->isAdmin())
                            <div class="creator-info">
                                <i class="bi bi-person me-1"></i>Created by: {{ $project->user->name ?? 'Unknown' }}
                            </div>
                        @endif

                        {{-- Show assignment info for employees --}}
                        @if(!Auth::user()->isAdmin())
                            <div class="assignment-info">
                                <i class="bi bi-person-gear me-1"></i>
                                @if($project->user_id == Auth::id())
                                    Created by you
                                @else
                                    Assigned by: {{ $project->user->name ?? 'Admin' }}
                                @endif
                            </div>
                        @endif

                        <p class="card-text text-secondary mb-3 flex-grow-1">
                            {{ $project->description ? Str::limit($project->description, 120) : 'No description provided' }}
                        </p>

                        <div class="task-stats">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="task-stat-item">
                                        <div class="task-stat-number text-warning">{{ $project->to_do_tasks ?? 0 }}</div>
                                        <small>To Do</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="task-stat-item">
                                        <div class="task-stat-number text-info">{{ $project->in_progress_tasks ?? 0 }}</div>
                                        <small>In Progress</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="task-stat-item">
                                        <div class="task-stat-number text-success">{{ $project->completed_tasks ?? 0 }}</div>
                                        <small>Completed</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="deadline-text mb-1">
                                <i class="bi bi-calendar3 me-1"></i>
                                <strong>Start:</strong> 
                                {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}
                            </div>
                            <div class="deadline-text">
                                <i class="bi bi-flag me-1"></i>
                                <strong>Deadline:</strong>
                                @if($project->end_date)
                                    @if($project->end_date->isFuture())
                                        <span class="text-primary">{{ $project->end_date->format('M d, Y') }}</span>
                                        <small class="text-muted">({{ $project->end_date->diffForHumans() }})</small>
                                    @else
                                        <span class="text-danger">{{ $project->end_date->format('M d, Y') }} <i class="bi bi-clock-history ms-1"></i></span>
                                    @endif
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </div>
                        </div>

                        {{-- Assigned Members --}}
                        <div class="assigned-members">
                            @if($project->teamMembers && $project->teamMembers->count() > 0)
                                <strong class="small">
                                    <i class="bi bi-people me-1"></i>
                                    @if(Auth::user()->isAdmin())
                                        Assigned to:
                                    @else
                                        Team Members:
                                    @endif
                                </strong>
                                <div class="mt-1">
                                    @foreach($project->teamMembers->take(3) as $member)
                                        <span class="member-badge {{ $member->id == Auth::id() ? 'border border-success' : '' }}" 
                                              title="{{ $member->email }} ({{ $member->id == Auth::id() ? 'You' : $member->name }})">
                                            {{ $member->name }}
                                            @if($member->id == Auth::id())
                                                <i class="bi bi-person-fill-check text-success ms-1"></i>
                                            @endif
                                        </span>
                                    @endforeach
                                    @if($project->teamMembers->count() > 3)
                                        <span class="member-badge" title="{{ $project->teamMembers->skip(3)->pluck('name')->join(', ') }}">
                                            +{{ $project->teamMembers->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted small">
                                    <i class="bi bi-people me-1"></i>
                                    No team members assigned
                                </span>
                            @endif
                        </div>

                        <div class="action-group mt-auto pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('projects.tasks.index', $project->id) }}" class="btn btn-info btn-sm" title="View Tasks">
                                        <i class="bi bi-list-task"></i>
                                    </a>
                                </div>
                                
                                @if(Auth::user()->isAdmin())
                                <div>
                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-sm" title="Edit Project">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete the project: {{ addslashes($project->name) }}? This action cannot be undone.')"
                                            title="Delete Project">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-folder-x display-1 text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">
                        {{ Auth::user()->isAdmin() ? 'No projects found' : 'No projects assigned to you' }}
                    </h4>
                    <p class="text-muted mb-4">
                        {{ Auth::user()->isAdmin() ? 'Get started by creating your first project to organize your work and assign team members.' : "You haven't been assigned to any projects yet. Please contact your administrator." }}
                    </p>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('projects.create') }}" class="btn btn-accent mt-2">
                            <i class="bi bi-plus-circle me-1"></i> Create Your First Project
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($projects->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Add loading state to buttons
    const deleteForms = document.querySelectorAll('form[action*="destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="bi bi-trash"></i> Deleting...';
            button.disabled = true;
        });
    });
});
</script>
@endsection