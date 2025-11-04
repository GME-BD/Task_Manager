@extends('layouts.app')

@section('title')
    Create New Task
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

    .project-info-sidebar {
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

    .project-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .project-link:hover {
        text-decoration: underline;
    }

    .priority-option {
        padding: 0.5rem;
        border-radius: 0.5rem;
        margin-bottom: 0.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .priority-option:hover {
        background-color: #f8f9fa;
    }

    .priority-option.selected {
        background-color: #e3f2fd;
        border-left: 4px solid var(--primary-color);
    }

    .is-valid {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25) !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
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
                    <i class="bi bi-plus-circle me-2"></i>Create New Task
                </h1>
                <p class="header-subtitle mb-0">
                    Add a new task to organize and track your work
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Go Back
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
                        <i class="bi bi-clipboard-plus me-2"></i>Task Information
                    </h2>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <!-- Task Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" 
                                   value="{{ old('title') }}" 
                                   placeholder="Enter a clear and descriptive task title" 
                                   required>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                      placeholder="Describe the task details, requirements, and objectives">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Project Selection -->
                        <div class="mb-4">
                            <label for="project_id" class="form-label">Project <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-select" required>
                                <option value="">Select a Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                        @if($project->teamMembers->count() > 0)
                                            ({{ $project->teamMembers->count() }} team members)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Assign To -->
                        <div class="mb-4">
                            <label for="user_id" class="form-label">Assign To <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Select Team Member</option>
                                <option value="{{ auth()->id() }}" {{ old('user_id') == auth()->id() ? 'selected' : '' }}>
                                    Self ({{ auth()->user()->name }})
                                </option>
                                <!-- Team members will be loaded dynamically based on project selection -->
                            </select>
                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dates and Priority Row -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" 
                                           value="{{ old('due_date') }}"
                                           min="{{ date('Y-m-d') }}">
                                    <small class="text-muted">Optional deadline for this task</small>
                                    @error('due_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                    <select name="priority" id="priority" class="form-select" required>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
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
                                <option value="to_do" {{ old('status') == 'to_do' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Create Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="project-info-sidebar">
                <h5 class="mb-4">
                    <i class="bi bi-lightbulb me-2"></i>Quick Tips
                </h5>

                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-check-circle text-success me-2"></i>Clear Titles
                    </div>
                    <div class="info-value">
                        Use specific, action-oriented titles that clearly describe the task.
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-check-circle text-success me-2"></i>Detailed Descriptions
                    </div>
                    <div class="info-value">
                        Include all necessary details, requirements, and acceptance criteria.
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-check-circle text-success me-2"></i>Realistic Due Dates
                    </div>
                    <div class="info-value">
                        Set achievable deadlines to keep the project on track.
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="bi bi-check-circle text-success me-2"></i>Proper Priority
                    </div>
                    <div class="info-value">
                        Assign appropriate priority to help with task scheduling and focus.
                    </div>
                </div>

                <!-- Priority Guide -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="mb-3">
                        <i class="bi bi-flag me-2"></i>Priority Guide
                    </h6>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-danger me-2">High</span>
                            <small>Urgent and critical tasks</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning me-2">Medium</span>
                            <small>Important but not urgent</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">Low</span>
                            <small>Nice to have, can wait</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const projectSelect = document.getElementById('project_id');
    const userSelect = document.getElementById('user_id');
    const projects = @json($projects->keyBy('id'));

    // Function to update team members based on selected project
    function updateTeamMembers() {
        const projectId = projectSelect.value;
        userSelect.innerHTML = '<option value="">Select Team Member</option>';
        
        if (projectId && projects[projectId]) {
            const project = projects[projectId];
            const teamMembers = project.team_members || [];
            
            // Add self option
            const selfOption = document.createElement('option');
            selfOption.value = '{{ auth()->id() }}';
            selfOption.textContent = 'Self ({{ auth()->user()->name }})';
            if ('{{ old('user_id') }}' === '{{ auth()->id() }}') {
                selfOption.selected = true;
            }
            userSelect.appendChild(selfOption);
            
            // Add team members
            teamMembers.forEach(member => {
                const option = document.createElement('option');
                option.value = member.id;
                option.textContent = member.name;
                if ('{{ old('user_id') }}' === member.id.toString()) {
                    option.selected = true;
                }
                userSelect.appendChild(option);
            });
        }
    }

    // Initialize team members on page load
    updateTeamMembers();

    // Update team members when project changes
    projectSelect.addEventListener('change', updateTeamMembers);

    // Real-time validation
    const titleInput = document.getElementById('title');
    const dueDateInput = document.getElementById('due_date');
    
    titleInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        }
    });

    dueDateInput.addEventListener('change', function() {
        if (this.value) {
            const dueDate = new Date(this.value);
            const today = new Date();
            
            if (dueDate < today) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });

    // Set minimum date for due date to today
    const today = new Date().toISOString().split('T')[0];
    dueDateInput.min = today;

    // Priority selection enhancement
    const prioritySelect = document.getElementById('priority');
    prioritySelect.addEventListener('change', function() {
        this.classList.add('is-valid');
    });

    // Project selection validation
    projectSelect.addEventListener('change', function() {
        if (this.value) {
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
        }
    });

    // User selection validation
    userSelect.addEventListener('change', function() {
        if (this.value) {
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
        }
    });
});
</script>
@endsection