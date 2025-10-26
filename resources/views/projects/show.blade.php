@extends('layouts.app')

@section('title')
    {{ $project->name }} - Project Details 📋
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #007bff;
            /* Vibrant Blue */
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
            text-align: center;
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
        }

        .btn-modal-trigger:hover {
            opacity: 0.9;
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
    </style>
@endsection

@section('content')
    <div class="container py-4">
        {{-- Project Header --}}
        <div class="page-header mb-5">
            <h2 class="m-0"><i class="bi bi-info-circle me-2"></i> Details: <span
                    class="text-primary">{{ $project->name }}</span></h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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

                        <p class="card-text text-muted mb-4">{{ $project->description }}</p>

                        {{-- Details List --}}
                        <div class="detail-item">
                            <strong><i class="bi bi-calendar-check me-2"></i> Start Date:</strong>
                            {{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}
                        </div>
                        <div class="detail-item">
                            <strong><i class="bi bi-flag me-2"></i> End Date:</strong>
                            {{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}
                        </div>
                        <div class="detail-item">
                            <strong><i class="bi bi-activity me-2"></i> Status:</strong>
                            @php
                                $statusText = $project->status == 'pending' ? 'Pending' : ($project->status == 'on_going' ? 'In Progress' : 'Completed');
                                $statusClass = $project->status == 'pending' ? 'bg-warning text-dark' : ($project->status == 'on_going' ? 'bg-info text-white' : 'bg-success text-white');
                            @endphp
                            <span class="detail-status-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        {{-- <div class="detail-item">
                            <strong><i class="bi bi-currency-dollar me-2"></i> Budget:</strong>

                            ${{ number_format($project->budget ?? 0, 2) }}
                        </div> --}}

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
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square me-1"></i> Edit Employee
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMN 2: Team Members --}}
            <div class="col-md-5 mb-4">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">  
                            <h5 class="fw-bold m-0"><i class="bi bi-people me-2"></i> Team Members</h5>
                            <button type="button" class="btn btn-modal-trigger shadow" data-bs-toggle="modal"
                                data-bs-target="#addMemberModal">
                                <i class="bi bi-plus-circle me-1"></i> Add Member
                            </button>
                        </div> --}}

                        <div class="member-list">
                            @forelse ($teamMembers as $user)
                                <div class="member-list-item">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle member-icon"></i>
                                        <span class="member-name">{{ $user->name }}</span>
                                    </div>
                                    <span class="badge bg-secondary">
                                        {{ $user->tasks->where('project_id', $project->id)->count() }} Tasks
                                    </span>
                                </div>
                            @empty
                                <div class="alert alert-light text-center" role="alert">
                                    No members assigned yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Member Modal (Styled) --}}
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 1rem;">
                <form action="{{ route('projects.addMember')}}" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="addMemberModalLabel">Add Team Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-bold">Select User</label>
                            <select class="form-select" name="user_id" id="user_id" style="border-radius: 0.75rem;">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 0.75rem;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: var(--accent-gradient); border: none; border-radius: 0.75rem; font-weight: 600;">Add
                            Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection