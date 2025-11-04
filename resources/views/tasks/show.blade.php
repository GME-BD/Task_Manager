@extends('layouts.app')
@section('title')
    {{ $task->title }} - Task Details
@endsection
@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('projects.tasks.index', $task->project->id) }}" class="btn btn-light btn-sm me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h2 class="h4 mb-0 fw-bold text-dark">{{ $task->title }}</h2>
                    <small class="text-muted">Task Details</small>
                </div>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTaskModal">
                <i class="bi bi-pencil-square me-2"></i>Edit Task
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column - Task Details -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-semibold mb-3 d-flex align-items-center">
                            <i class="bi bi-card-text me-2 text-primary"></i>Description
                        </h5>
                        <p class="card-text text-muted mb-4">{{ $task->description ?: 'No description provided' }}</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-2 text-muted"></i>
                                    <div>
                                        <small class="text-muted">Due Date</small>
                                        <p class="mb-0 fw-semibold {{ \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'completed' ? 'text-danger' : 'text-dark' }}">
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('M j, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-flag me-2 text-muted"></i>
                                    <div>
                                        <small class="text-muted">Priority</small>
                                        <p class="mb-0">
                                            <span class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }} rounded-pill">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-circle-half me-2 text-muted"></i>
                                    <div>
                                        <small class="text-muted">Status</small>
                                        <p class="mb-0">
                                            @if ($task->status == 'completed')
                                                <span class="badge bg-success rounded-pill">Completed</span>
                                            @elseif($task->status == 'to_do')
                                                <span class="badge bg-secondary rounded-pill">To Do</span>
                                            @elseif($task->status == 'in_progress')
                                                <span class="badge bg-primary rounded-pill">In Progress</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person me-2 text-muted"></i>
                                    <div>
                                        <small class="text-muted">Assign by</small>
                                        <p class="mb-0 fw-semibold text-dark">{{ $task->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasklist Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-semibold mb-0 d-flex align-items-center">
                                <i class="bi bi-list-check me-2 text-primary"></i>Tasklist
                            </h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addChecklistModal">
                                <i class="bi bi-plus-circle me-1"></i>Add Item
                            </button>
                        </div>

                        @if($task->checklistItems->count() > 0)
                            <div class="list-group list-group-flush" id="checklist-items">
                                @foreach ($task->checklistItems as $item)
                                    <div class="list-group-item px-0 py-3 border-0" id="checklist-item-{{ $item->id }}">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="checklist-item-checkbox-{{ $item->id }}"
                                                    {{ $item->completed ? 'checked' : '' }}
                                                    onchange="toggleChecklistItem({{ $item->id }})"
                                                    style="width: 1.2em; height: 1.2em;">
                                            </div>
                                            <label class="form-check-label flex-grow-1 {{ $item->completed ? 'text-decoration-line-through text-muted' : 'text-dark' }}">
                                                {{ $item->name }}
                                            </label>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <form method="POST" action="{{ route('checklist-items.destroy', $item->id) }}" 
                                                              class="d-inline delete-checklist-form" data-item-id="{{ $item->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-list-check display-4 text-muted mb-3"></i>
                                <p class="text-muted">No tasklist items yet. Add your first one!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Time Tracker & Actions -->
            <div class="col-lg-4">
                <!-- Time Tracker -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-semibold mb-3 d-flex align-items-center">
                            <i class="bi bi-clock me-2 text-primary"></i>Time Tracker
                        </h5>
                        <div id="time-tracker" class="text-center">
                            <div class="display-4 fw-bold text-primary mb-3" id="time-display">00:00:00</div>
                            <div class="d-flex justify-content-center gap-2">
                                <button id="start-btn" class="btn btn-success btn-sm rounded-pill px-3">
                                    <i class="bi bi-play-fill me-1"></i>Start
                                </button>
                                <button id="pause-btn" class="btn btn-warning btn-sm rounded-pill px-3">
                                    <i class="bi bi-pause-fill me-1"></i>Pause
                                </button>
                                <button id="reset-btn" class="btn btn-danger btn-sm rounded-pill px-3">
                                    <i class="bi bi-stop-fill me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-semibold mb-3 d-flex align-items-center">
                            <i class="bi bi-lightning me-2 text-primary"></i>Quick Actions
                        </h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('projects.tasks.index', $task->project->id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-90deg-left me-2"></i>Back to Tasks
                            </a>
                            <button class="btn btn-outline-success" onclick="markTaskComplete()">
                                <i class="bi bi-check-circle me-2"></i>Mark Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Checklist Modal -->
    <div class="modal fade" id="addChecklistModal" tabindex="-1" aria-labelledby="addChecklistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="add-checklist-form">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addChecklistModalLabel">
                            <i class="bi bi-plus-circle me-2"></i>Add Tasklist Item
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="checklist-name" class="form-label fw-semibold">Task Name</label>
                            <input type="text" name="name" id="checklist-name" class="form-control" placeholder="Enter task name" required>
                            <div class="invalid-feedback" id="checklist-name-error"></div>
                        </div>
                        <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editTaskModalLabel">
                            <i class="bi bi-pencil-square me-2"></i>Edit Task
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $task->title }}" required>
                            @error('title')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Add a description">{{ $task->description }}</textarea>
                            @error('description')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label fw-semibold">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $task->due_date }}">
                                @error('due_date')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label fw-semibold">Priority</label>
                                <select name="priority" id="priority" class="form-select" required>
                                    <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="to_do" {{ $task->status == 'to_do' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .form-check-input:checked {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .list-group-item {
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }
        
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
        }
        
        #time-display {
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }
        
        .dropdown-toggle::after {
            display: none;
        }
    </style>

    <script>
        let timer;
        let seconds = 0;
        let isRunning = false;

        function formatTime(sec) {
            let hours = Math.floor(sec / 3600);
            let minutes = Math.floor((sec % 3600) / 60);
            let seconds = sec % 60;

            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        function updateTimeDisplay() {
            document.getElementById('time-display').innerText = formatTime(seconds);
        }

        document.getElementById('start-btn').addEventListener('click', () => {
            if (!isRunning) {
                isRunning = true;
                timer = setInterval(() => {
                    seconds++;
                    updateTimeDisplay();
                }, 1000);
                document.getElementById('start-btn').disabled = true;
                document.getElementById('pause-btn').disabled = false;
            }
        });

        document.getElementById('pause-btn').addEventListener('click', () => {
            if (isRunning) {
                isRunning = false;
                clearInterval(timer);
                document.getElementById('start-btn').disabled = false;
            }
        });

        document.getElementById('reset-btn').addEventListener('click', () => {
            isRunning = false;
            clearInterval(timer);
            seconds = 0;
            updateTimeDisplay();
            document.getElementById('start-btn').disabled = false;
            document.getElementById('pause-btn').disabled = false;
        });

        // Initialize button states
        document.getElementById('pause-btn').disabled = true;
        updateTimeDisplay();

        function toggleChecklistItem(itemId) {
            const url = '{{ route('checklist-items.update-status', ':id') }}'.replace(':id', itemId);
            const checkbox = document.getElementById(`checklist-item-checkbox-${itemId}`);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ completed: checkbox.checked })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const label = checkbox.closest('.form-check').querySelector('.form-check-label');
                    if (checkbox.checked) {
                        label.classList.add('text-decoration-line-through', 'text-muted');
                    } else {
                        label.classList.remove('text-decoration-line-through', 'text-muted');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Delete checklist item with confirmation
        document.querySelectorAll('.delete-checklist-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const itemId = this.getAttribute('data-item-id');
                
                if (confirm('Are you sure you want to delete this task?')) {
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: '_method=DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`checklist-item-${itemId}`).remove();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        // AJAX for adding checklist item
        document.getElementById('add-checklist-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch('{{ route('checklist-items.store', $task->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const checklistItems = document.getElementById('checklist-items');
                    
                    // If no items exist, replace the empty state
                    if (checklistItems.children.length === 0 || checklistItems.querySelector('.text-center')) {
                        checklistItems.innerHTML = '';
                    }
                    
                    const checklistItem = document.createElement('div');
                    checklistItem.className = 'list-group-item px-0 py-3 border-0';
                    checklistItem.id = `checklist-item-${data.id}`;
                    checklistItem.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" 
                                    id="checklist-item-checkbox-${data.id}"
                                    onchange="toggleChecklistItem(${data.id})"
                                    style="width: 1.2em; height: 1.2em;">
                            </div>
                            <label class="form-check-label flex-grow-1 text-dark">
                                ${data.name}
                            </label>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form method="POST" action="{{ route('checklist-items.destroy', '') }}/${data.id}" 
                                              class="d-inline delete-checklist-form" data-item-id="${data.id}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    `;

                    checklistItems.appendChild(checklistItem);
                    
                    // Add event listener for the new delete button
                    const deleteForm = checklistItem.querySelector('.delete-checklist-form');
                    deleteForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const itemId = this.getAttribute('data-item-id');
                        
                        if (confirm('Are you sure you want to delete this task?')) {
                            fetch(this.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: '_method=DELETE'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById(`checklist-item-${itemId}`).remove();
                                    
                                    // If no items left, show empty state
                                    if (checklistItems.children.length === 0) {
                                        checklistItems.innerHTML = `
                                            <div class="text-center py-5">
                                                <i class="bi bi-list-check display-4 text-muted mb-3"></i>
                                                <p class="text-muted">No tasklist items yet. Add your first one!</p>
                                            </div>
                                        `;
                                    }
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    });
                    
                    form.reset();
                    document.querySelector('#addChecklistModal .btn-close').click();
                } else {
                    const errorElement = document.getElementById('checklist-name-error');
                    errorElement.textContent = data.message;
                    errorElement.style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
        });

        function markTaskComplete() {
            document.getElementById('status').value = 'completed';
            document.querySelector('#editTaskModal form').submit();
        }
    </script>
@endsection