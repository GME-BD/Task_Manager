@extends('layouts.app')

@section('title')
    {{ $project->name }} - Tasks
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    .kanban-column {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        height: 100%;
    }

    .kanban-list {
        min-height: 500px;
        background-color: #e9ecef;
        border-radius: 5px;
        padding: 10px;
    }

    .kanban-item {
        cursor: move;
        transition: all 0.3s ease;
    }

    .kanban-item.invisible {
        opacity: 0.4;
    }

    .kanban-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .priority-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    .task-card {
        border-left: 4px solid #007bff;
    }

    .task-card.high-priority {
        border-left-color: #dc3545;
    }

    .task-card.medium-priority {
        border-left-color: #ffc107;
    }

    .task-card.low-priority {
        border-left-color: #28a745;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="bg-white align-items-center mb-4 shadow-sm p-3 rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ $project->name }} - Task Board</h2>
                    <p class="text-muted mb-0">Manage and track all project tasks</p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6">
                        Total Tasks: {{ ($tasks['to_do']->count() ?? 0) + ($tasks['in_progress']->count() ?? 0) + ($tasks['completed']->count() ?? 0) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Kanban Board -->
        <div class="row">
            <!-- To Do Column -->
            <div class="col-md-4 mb-4">
                <div class="kanban-column h-100">
                    <div class="d-flex justify-content-between bg-primary text-white shadow-sm align-items-center px-3 py-2 rounded-top">
                        <h5 class="text-white fw-bold m-0">
                            <i class="bi bi-circle-dashed me-2"></i>To Do
                            <span class="badge bg-light text-primary ms-2">{{ $tasks['to_do']->count() ?? 0 }}</span>
                        </h5>
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createTaskModal"
                            data-status="to_do" title="Add Task">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    
                    <div class="kanban-list" id="to_do">
                        @forelse ($tasks['to_do'] ?? [] as $task)
                            <div class="card mb-3 kanban-item task-card {{ $task->priority }}-priority" 
                                 data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title fw-bold mb-0">{{ $task->title }}</h6>
                                        <span class="badge priority-badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 80) }}</p>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="task-meta">
                                            @if($task->due_date)
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                                </small>
                                            @endif
                                            @if($task->user)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $task->user->name }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="task-actions">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-outline-primary btn-sm" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-clipboard-x"></i>
                                <p class="mb-0">No tasks in this column</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="col-md-4 mb-4">
                <div class="kanban-column h-100">
                    <div class="d-flex justify-content-between bg-warning text-white shadow-sm align-items-center px-3 py-2 rounded-top">
                        <h5 class="text-white fw-bold m-0">
                            <i class="bi bi-arrow-repeat me-2"></i>In Progress
                            <span class="badge bg-light text-warning ms-2">{{ $tasks['in_progress']->count() ?? 0 }}</span>
                        </h5>
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal"
                            data-bs-target="#createTaskModal" data-status="in_progress" title="Add Task">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    
                    <div class="kanban-list" id="in_progress">
                        @forelse ($tasks['in_progress'] ?? [] as $task)
                            <div class="card mb-3 kanban-item task-card {{ $task->priority }}-priority" 
                                 data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title fw-bold mb-0">{{ $task->title }}</h6>
                                        <span class="badge priority-badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 80) }}</p>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="task-meta">
                                            @if($task->due_date)
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                                </small>
                                            @endif
                                            @if($task->user)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $task->user->name }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="task-actions">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-outline-warning btn-sm" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-arrow-repeat"></i>
                                <p class="mb-0">No tasks in progress</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Completed Column -->
            <div class="col-md-4 mb-4">
                <div class="kanban-column h-100">
                    <div class="d-flex justify-content-between bg-success text-white shadow-sm align-items-center px-3 py-2 rounded-top">
                        <h5 class="text-white fw-bold m-0">
                            <i class="bi bi-check-circle me-2"></i>Completed
                            <span class="badge bg-light text-success ms-2">{{ $tasks['completed']->count() ?? 0 }}</span>
                        </h5>
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal"
                            data-bs-target="#createTaskModal" data-status="completed" title="Add Task">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    <div class="kanban-list" id="completed">
                        @forelse ($tasks['completed'] ?? [] as $task)
                            <div class="card mb-3 kanban-item task-card {{ $task->priority }}-priority" 
                                 data-id="{{ $task->id }}" draggable="true">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title fw-bold mb-0">{{ $task->title }}</h6>
                                        <span class="badge priority-badge bg-secondary">
                                            Completed
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 80) }}</p>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="task-meta">
                                            @if($task->due_date)
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                                </small>
                                            @endif
                                            @if($task->user)
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $task->user->name }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="task-actions">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-outline-success btn-sm" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-check-circle"></i>
                                <p class="mb-0">No completed tasks</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Task Modal -->
        <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('projects.tasks.store', $project->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="createTaskModalLabel">
                                <i class="bi bi-plus-circle me-2"></i>Create New Task
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control" 
                                               placeholder="Enter task title" required value="{{ old('title') }}">
                                        @error('title')
                                            <span class="text-danger small">{{ $message }}</span>
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
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3" 
                                          placeholder="Task description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">Due Date</label>
                                        <input type="date" name="due_date" id="due_date" class="form-control" 
                                               value="{{ old('due_date') }}">
                                        @error('due_date')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Assign To <span class="text-danger">*</span></label>
                                        <select name="user_id" id="user_id" class="form-select" required>
                                            <option value="">Select Team Member</option>
                                            <option value="{{ auth()->user()->id }}" {{ old('user_id') == auth()->id() ? 'selected' : '' }}>
                                                Self ({{ auth()->user()->name }})
                                            </option>
                                            @foreach ($users as $user)  
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror 
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="status" id="task_status" value="{{ old('status', 'to_do') }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const kanbanItems = document.querySelectorAll('.kanban-item');
            const kanbanLists = document.querySelectorAll('.kanban-list');
            const createTaskModal = document.getElementById('createTaskModal');
            const taskStatusInput = document.getElementById('task_status');

            // Set task status when modal is opened
            createTaskModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; 
                var status = button.getAttribute('data-status'); 
                taskStatusInput.value = status;
            });

            // Drag and drop functionality
            kanbanItems.forEach(item => {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragend', handleDragEnd);
            });

            kanbanLists.forEach(list => {
                list.addEventListener('dragover', handleDragOver);
                list.addEventListener('drop', handleDrop);
                list.addEventListener('dragenter', handleDragEnter);
                list.addEventListener('dragleave', handleDragLeave);
            });

            function handleDragStart(e) {
                e.dataTransfer.setData('text/plain', e.target.dataset.id);
                e.target.classList.add('invisible');
            }

            function handleDragEnd(e) {
                e.target.classList.remove('invisible');
            }

            function handleDragOver(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            }

            function handleDragEnter(e) {
                e.preventDefault();
                e.target.closest('.kanban-list').classList.add('bg-info', 'bg-opacity-10');
            }

            function handleDragLeave(e) {
                e.target.closest('.kanban-list').classList.remove('bg-info', 'bg-opacity-10');
            }

            function handleDrop(e) {
                e.preventDefault();
                const id = e.dataTransfer.getData('text');
                const draggableElement = document.querySelector(`.kanban-item[data-id='${id}']`);
                const dropzone = e.target.closest('.kanban-list');
                
                // Remove highlight
                dropzone.classList.remove('bg-info', 'bg-opacity-10');
                
                // Append the dragged element
                dropzone.appendChild(draggableElement);

                const status = dropzone.id;

                // Update task status via AJAX
                updateTaskStatus(id, status);
            }

            function updateTaskStatus(id, status) {
                fetch(`/tasks/${id}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to update task status');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Task status updated successfully:', data);
                    // Show success notification
                    showNotification('Task status updated!', 'success');
                })
                .catch(error => {
                    console.error('Error updating task status:', error);
                    // Show error notification
                    showNotification('Failed to update task status', 'error');
                });
            }

            function showNotification(message, type) {
                // Create a simple notification
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(notification);

                // Auto remove after 3 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 3000);
            }
        });
    </script>
@endsection