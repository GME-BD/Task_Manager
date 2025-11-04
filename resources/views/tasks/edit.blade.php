@extends('layouts.app')

@section('title')
    Edit Task - {{ $task->title }}
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

    .container {
        padding-top: 2rem;
        padding-bottom: 2rem;
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
        margin-bottom: 0.5rem;
    }

    .header-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
    }

    .card {
        border: none;
        border-radius: var(--card-border-radius);
        box-shadow: var(--card-shadow);
    }

    .card-header {
        background: var(--accent-gradient);
        color: white;
        border-radius: var(--card-border-radius) var(--card-border-radius) 0 0 !important;
        padding: 1.25rem 1.5rem;
        border: none;
    }

    .card-title {
        font-weight: 600;
        margin-bottom: 0;
        font-size: 1.25rem;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        transform: translateY(-2px);
    }

    .btn-primary {
        background: var(--accent-gradient);
        border: none;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        transition: opacity 0.3s ease, transform 0.2s ease;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    }

    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
        font-weight: 500;
        border-radius: 0.75rem;
        padding: 0.75rem 2rem;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
        transform: translateY(-1px);
    }

    .text-danger {
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 0.25rem;
    }

    .task-info-sidebar {
        background: #f8f9fa;
        border-radius: var(--card-border-radius);
        padding: 1.5rem;
    }

    .info-item {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .status-badge {
        font-weight: 600;
        padding: 0.35em 0.7em;
        border-radius: 0.5rem;
        font-size: 0.85rem;
    }

    .status-to_do { background-color: #6c757d; color: white; }
    .status-in_progress { background-color: #17a2b8; color: white; }
    .status-completed { background-color: #28a745; color: white; }

    .priority-badge {
        font-weight: 600;
        padding: 0.35em 0.7em;
        border-radius: 0.5rem;
        font-size: 0.85rem;
    }

    .priority-low { background-color: #28a745; color: white; }
    .priority-medium { background-color: #ffc107; color: #343a40; }
    .priority-high { background-color: #dc3545; color: white; }

    .project-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .project-link:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="header-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Task
                </h1>
                <p class="header-subtitle mb-0">
                    Update task details for: <strong>"{{ $task->title }}"</strong>
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('projects.tasks.index', $task->project_id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Tasks
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0">
                        <i class="bi bi-tools me-2"></i>Task Details
                    </h2>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Task Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" 
                                   value="{{ old('title', $task->title) }}" 
                                   placeholder="Enter task title" required>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                      placeholder="Describe the task details">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dates and Priority Row -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" 
                                           value="{{ old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}">
                                    @error('due_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                    <select name="priority" id="priority" class="form-select" required>
                                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                                    </select>
                                    @error('priority')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="to_do" {{ old('status', $task->status) == 'to_do' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('projects.tasks.index', $task->project_id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="task-info-sidebar">
                <h5 class="mb-4">
                    <i class="bi bi-info-circle me-2"></i>Task Information
                </h5>

                <!-- Project Info -->
                <div class="info-item">
                    <div class="info-label">Project</div>
                    <div class="info-value">
                        <a href="{{ route('projects.show', $task->project_id) }}" class="project-link">
                            <i class="bi bi-folder me-1"></i>{{ $task->project->name }}
                        </a>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="info-item">
                    <div class="info-label">Current Status</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $task->status }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                </div>

                <!-- Current Priority -->
                <div class="info-item">
                    <div class="info-label">Current Priority</div>
                    <div class="info-value">
                        <span class="priority-badge priority-{{ $task->priority }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>

                <!-- Assigned To -->
                <div class="info-item">
                    <div class="info-label">Assigned To</div>
                    <div class="info-value">
                        <i class="bi bi-person me-1"></i>
                        {{ $task->user->name ?? 'Unassigned' }}
                    </div>
                </div>

                <!-- Created Date -->
                <div class="info-item">
                    <div class="info-label">Created</div>
                    <div class="info-value">
                        <i class="bi bi-calendar me-1"></i>
                        {{ $task->created_at->format('M d, Y') }}
                    </div>
                </div>

                <!-- Last Updated -->
                <div class="info-item">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">
                        <i class="bi bi-clock me-1"></i>
                        {{ $task->updated_at->format('M d, Y g:i A') }}
                    </div>
                </div>

                <!-- Due Date Warning -->
                @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'completed')
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This task is overdue!
                </div>
                @endif

                @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isFuture() && $task->status == 'completed')
                <div class="alert alert-success mt-3 mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Completed ahead of schedule!
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    
    titleInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });

    // Update due date validation
    const dueDateInput = document.getElementById('due_date');
    if (dueDateInput.value) {
        const dueDate = new Date(dueDateInput.value);
        const today = new Date();
        
        if (dueDate < today) {
            dueDateInput.classList.add('is-invalid');
        } else {
            dueDateInput.classList.add('is-valid');
        }
    }

    dueDateInput.addEventListener('change', function() {
        const dueDate = new Date(this.value);
        const today = new Date();
        
        if (dueDate < today) {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
});
</script>
@endsection