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

        /* Modernize the header bar */
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
  
        /* Modern card styling */
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
        
        /* Status styling */
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

        /* Task stats */
        .task-stats {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin: 1rem 0;
        }

        .task-stat-item {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .task-stat-number {
            font-weight: 600;
            color: var(--primary-dark);
        }

        /* Action buttons group */
        .action-group .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            margin-right: 0.25rem;
        }

        /* Add Project button with accent gradient */
        .btn-accent {
            background: var(--accent-gradient);
            border: none;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            border-radius: 0.75rem;
            transition: opacity 0.3s ease;
            color: white;
        }

        .btn-accent:hover {
            opacity: 0.9;
            color: white;
        }

        .deadline-text {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .text-danger {
            font-weight: 600;
        }

        .assigned-members {
            margin-top: 0.5rem;
        }

        .member-badge {
            background: #e9ecef;
            border-radius: 1rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            color: #495057;
        }

        .admin-badge {
            background: var(--primary-color);
            color: white;
        }

        .creator-info {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        /* Pagination styling */
        .pagination {
            margin-top: 2rem;
        }

        .page-link {
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            border: 1px solid #dee2e6;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        
        {{------------------------------------ Header Section -------------------------------------}}
        <div class="page-header d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="header-title mb-1">
                    <i class="bi bi-folder me-2"></i>
                    @auth
                        {{ Auth::user()->isAdmin() ? 'All Projects' : 'My Projects' }}
                    @endauth
                </h2>
                @auth
                    @if(Auth::user()->isAdmin())
                        <p class="text-muted mb-0">Manage all projects and assign them to team members</p>
                    @else
                        <p class="text-muted mb-0">Projects assigned to you</p>
                    @endif
                @endauth
            </div>
            
            @if(Auth::user()->isAdmin())
                <a href="{{ route('projects.create') }}" class="btn btn-accent shadow">
                    <i class="bi bi-plus-circle me-1"></i> Create Project
                </a>
            @endif
        </div>

        {{-------------------------------------- Success Alert --------------------------------------------}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{---------------------------------------- Projects Count ---------------------------------------------}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-light border-0" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    Showing <strong>{{ $projects->count() }}</strong> 
                    @if($projects->total() > $projects->count())
                        of <strong>{{ $projects->total() }}</strong>
                    @endif
                    project(s)
                </div>
            </div>
        </div>

        {{------------------------------------------ Projects Grid -----------------------------------------------}}
        <div class="row">
            @forelse($projects as $project)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body d-flex flex-column">
                            {{------------------------------ Project Title -------------------------------------}}
                            <h5 class="card-title">{{ $project->name }}</h5>
                            
                            {{---------------------------- Creator Info (for admin view) ---------------------}}
                            @if(Auth::user()->isAdmin())
                                <div class="creator-info">
                                    <i class="bi bi-person me-1"></i>Created by: {{ $project->user->name }}
                                </div>
                            @endif
                            
                            {{---------------------------------- Description ------------------------------------------}}
                            <p class="card-text text-secondary mb-3">
                                {{ $project->description ? Str::limit($project->description, 100) : 'No description provided' }}
                            </p> 
                            
                            {{------------------------------------ Status -----------------------------------------------}}
                            <div class="mb-2">
                                <strong>Status:</strong>
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
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>

                            {{------------------------------------- Task Statistics ----------------------------------------}}
                            <div class="task-stats">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="task-stat-item">
                                            <div class="task-stat-number">{{ $project->to_do_tasks ?? 0 }}</div>
                                            <small>To Do</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="task-stat-item">
                                            <div class="task-stat-number">{{ $project->in_progress_tasks ?? 0 }}</div>
                                            <small>In Progress</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="task-stat-item">
                                            <div class="task-stat-number">{{ $project->completed_tasks ?? 0 }}</div>
                                            <small>Completed</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-------------------------------------------- Dates -------------------------------------}}
                            <div class="mb-3">
                                <div class="deadline-text mb-1">
                                    <strong>Start:</strong> 
                                    {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}
                                </div>
                                <div class="deadline-text">
                                    <strong>Deadline:</strong>
                                    @if($project->end_date)
                                        @if($project->end_date->isFuture())
                                            <span class="text-primary">{{ $project->end_date->format('M d, Y') }}</span>
                                            <small class="text-muted">({{ $project->end_date->diffForHumans() }})</small>
                                        @else
                                            <span class="text-danger">
                                                {{ $project->end_date->format('M d, Y') }}
                                                <i class="bi bi-clock-history ms-1"></i>
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </div>
                            </div>

                            {{----------------------------------- Assigned Members -----------------------------------}}
                            @if($project->teamMembers && $project->teamMembers->count() > 0)
                                <div class="assigned-members">
                                    <strong class="small">Assigned to:</strong>
                                    <div class="mt-1">
                                        @foreach($project->teamMembers->take(3) as $member)
                                            <span class="member-badge d-inline-block me-1 mb-1">
                                                {{ $member->name }}
                                            </span>
                                        @endforeach
                                        @if($project->teamMembers->count() > 3)
                                            <span class="member-badge d-inline-block me-1 mb-1">
                                                +{{ $project->teamMembers->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="assigned-members">
                                    <span class="text-muted small">No team members assigned</span>
                                </div>
                            @endif
                            
                            {{----------------------------------- Action Buttons ------------------------------------}}
                            <div class="action-group mt-auto pt-3">
                                {{----------------------------- View Project Details ---------------------------------}}
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                {{-------------------------------------- View Tasks --}}
                                <a href="{{ route('projects.tasks.index', $project->id) }}" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="View Tasks">
                                    <i class="bi bi-list-task"></i>
                                </a>
                                
                                {{-- Edit Project (Admin only) --}}
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Project">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    {{-- Delete Project (Admin only) --}}
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete the project: {{ $project->name }}?')" 
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Project">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">
                            @if(Auth::user()->isAdmin())
                                No projects found
                            @else
                                No projects assigned to you
                            @endif
                        </h4>
                        <p class="text-muted">
                            @if(Auth::user()->isAdmin())
                                Get started by creating your first project
                            @else
                                You haven't been assigned to any projects yet
                            @endif
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
                    <div class="d-flex">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Initialize Tooltips --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection
