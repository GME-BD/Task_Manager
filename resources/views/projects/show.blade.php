@extends('layouts.app')

@section('title')
    {{ $project->name }} - Project Details 📋
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #007bff;
            --primary-dark: #0056b3;
            --accent-gradient: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);
            --card-border-radius: 1.5rem;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --detail-color: #6c757d;
        }

        /* Header */
        .page-header {
            font-weight: 600;
            color: #343a40;
            padding: 1.5rem;
            border-radius: 1rem;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        /* Card Consistency */
        .card {
            border: none !important;
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .card-body {
            padding: 2rem;
        }

        /* Detail Elements */
        .project-detail-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--primary-dark);
        }

        .detail-item {
            margin-bottom: 1rem;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item strong {
            color: #343a40;
            font-weight: 600;
            min-width: 120px;
            display: inline-block;
        }

        .detail-status-badge {
            font-weight: 700;
            padding: 0.4em 0.8em;
            border-radius: 0.5rem;
        }

        /* Progress Bar Styling */
        .progress {
            height: 1.5rem;
            border-radius: 0.75rem;
            background-color: #e9ecef;
        }

        .progress-bar {
            background: var(--accent-gradient);
            font-weight: 600;
            transition: width 0.6s ease;
        }

        /* Team Member List */
        .member-list-item {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.75rem;
            border-left: 5px solid var(--primary-color);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
            justify-content: space-between;
        }

        .member-icon {
            font-size: 1.25rem;
            color: var(--primary-dark);
            margin-right: 1rem;
        }

        .member-name {
            font-weight: 600;
            color: #343a40;
        }

        /* Modal Button */
        .btn-modal-trigger {
            background: var(--accent-gradient);
            border: none;
            border-radius: 0.75rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            color: white;
        }

        .btn-modal-trigger:hover {
            opacity: 0.9;
            color: white;
        }

        /* Back Button */
        .btn-back {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            font-weight: 500;
            border-radius: 0.75rem;
            padding: 0.75rem 2rem;
        }

        .btn-back:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            color: white;
        }

        /* Status Colors */
        .status-not_started { background-color: #6c757d; color: white; }
        .status-in_progress { background-color: #17a2b8; color: white; }
        .status-completed { background-color: #28a745; color: white; }

        /* Task stats */
        .task-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .stat-card {
            background: #f8f9fa;
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            border-left: 4px solid var(--primary-color);
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            display: block;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .remove-member-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }

        .remove-member-btn:hover {
            background-color: #f8d7da;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        {{-- Project Header --}}
        <div class="page-header d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="m-0">
                    <i class="bi bi-info-circle me-2"></i> 
                    Project Details: <span class="text-primary">{{ $project->name }}</span>
                </h2>
                <p class="text-muted mb-0 mt-1">
                    <i class="bi bi-person me-1"></i>
                    Created by: {{ $project->user->name }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('projects.tasks.index', $project->id) }}" class="btn btn-primary">
                    <i class="bi bi-list-task me-1"></i> View Tasks
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> Edit Project
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- COLUMN 1: Project Information and Progress --}}
            <div class="col-md-7 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="project-detail-title">{{ $project->name }}</h4>

                        <p class="card-text text-muted mb-4">
                            {{ $project->description ?: 'No description provided' }}
                        </p>

                        {{-- Task Statistics --}}
                        <div class="task-stats-grid">
                            <div class="stat-card">
                                <span class="stat-number">{{ $project->tasks->where('status', 'to_do')->count() }}</span>
                                <span class="stat-label">To Do</span>
                            </div>
                            <div class="stat-card">
                                <span class="stat-number">{{ $project->tasks->where('status', 'in_progress')->count() }}</span>
                                <span class="stat-label">In Progress</span>
                            </div>
                            <div class="stat-card">
                                <span class="stat-number">{{ $project->tasks->where('status', 'completed')->count() }}</span>
                                <span class="stat-label">Completed</span>
                            </div>
                        </div>

                        {{-- Details List --}}
                        <div class="detail-item">
                            <strong><i class="bi bi-calendar-check me-2"></i> Start Date:</strong>
                            {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'Not set' }}
                        </div>
                        <div class="detail-item">
                            <strong><i class="bi bi-flag me-2"></i> End Date:</strong>
                            @if($project->end_date)
                                {{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}
                                @if($project->end_date->isPast())
                                    <span class="badge bg-danger ms-2">Overdue</span>
                                @endif
                            @else
                                Not set
                            @endif
                        </div>
                        <div class="detail-item">
                            <strong><i class="bi bi-activity me-2"></i> Status:</strong>
                            @php
                                $statusClass = [
                                    'not_started' => 'status-not_started',
                                    'in_progress' => 'status-in_progress',
                                    'completed' => 'status-completed',
                                ][$project->status] ?? 'bg-secondary';
                                
                                $statusText = [
                                    'not_started' => 'Not Started',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                ][$project->status] ?? 'Unknown';
                            @endphp
                            <span class="detail-status-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>

                        {{-- Budget (if exists) --}}
                        {{-- @if($project->budget)
                        <div class="detail-item">
                            <strong><i class="bi bi-currency-dollar me-2"></i> Budget:</strong>
                            ${{ number_format($project->budget, 2) }}
                        </div>
                        @endif --}}

                        {{-- Progress Bar --}}
                        <h5 class="mt-5 mb-3 fw-bold">Overall Progress</h5>
                        @php
                            $totalTasks = $project->tasks->count();
                            $completedTasks = $project->tasks->where('status', 'completed')->count();
                            $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                            $progress = round($progress);
                        @endphp

                        <p class="text-end fw-bold text-primary">{{ $progress }}% Complete ({{ $completedTasks }} of
                            {{ $totalTasks }} Tasks)</p>
                        <div class="progress mb-4" role="progressbar" aria-label="Project Progress"
                            aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: {{ $progress }}%;">
                                @if($progress > 10) {{ $progress }}% @endif
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('projects.index') }}" class="btn btn-back">
                                <i class="bi bi-arrow-left me-1"></i> Back to Projects
                            </a>
                            @if(Auth::user()->isAdmin())
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this project?')">
                                        <i class="bi bi-trash me-1"></i> Delete Project
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMN 2: Team Members --}}
            <div class="col-md-5 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">  
                            <h5 class="fw-bold m-0"><i class="bi bi-people me-2"></i> Team Members</h5>
                            @if(Auth::user()->isAdmin())
                                <button type="button" class="btn btn-modal-trigger shadow" data-bs-toggle="modal"
                                    data-bs-target="#addMemberModal">
                                    <i class="bi bi-plus-circle me-1"></i> Add Member
                                </button>
                            @endif
                        </div>

                        <div class="member-list">
                            @forelse ($teamMembers as $user)
                                <div class="member-list-item">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle member-icon"></i>
                                        <div>
                                            <span class="member-name">{{ $user->name }}</span>
                                            <br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary">
                                            {{ $user->tasks->where('project_id', $project->id)->count() }} Tasks
                                        </span>
                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('projects.remove-member', $project->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <button type="submit" class="remove-member-btn" 
                                                        onclick="return confirm('Remove {{ $user->name }} from this project?')"
                                                        data-bs-toggle="tooltip" title="Remove Member">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-light text-center" role="alert">
                                    <i class="bi bi-people display-4 text-muted mb-3"></i>
                                    <p class="mb-0">No team members assigned yet.</p>
                                    @if(Auth::user()->isAdmin())
                                        <small class="text-muted">Use the "Add Member" button to assign team members</small>
                                    @endif
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Member Modal --}}
    @if(Auth::user()->isAdmin())
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 1rem;">
                <form action="{{ route('projects.add-member', $project->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="addMemberModalLabel">Add Team Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-bold">Select Employee</label>
                            <select class="form-select" name="user_id" id="user_id" style="border-radius: 0.75rem;" required>
                                <option value="">Choose an employee...</option>
                                @foreach ($employees as $user)
                                    @if(!$teamMembers->contains($user))
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endif
                                @endforeach
                            </select>
                            @if($employees->count() === $teamMembers->count())
                                <div class="alert alert-info mt-2 mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    All employees are already assigned to this project.
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 0.75rem;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: var(--accent-gradient); border: none; border-radius: 0.75rem; font-weight: 600;"
                            {{ $employees->count() === $teamMembers->count() ? 'disabled' : '' }}>
                            Add Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto-show modal if there's an error in member assignment
            @if($errors->has('user_id'))
                var modal = new bootstrap.Modal(document.getElementById('addMemberModal'));
                modal.show();
            @endif
        });
    </script>
@endsection