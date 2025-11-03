@extends('layouts.app')

@section('title')
    Edit {{ $project->name }} - Project ✏️
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
        }

        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .page-header {
            font-weight: 600;
            color: #343a40;
            padding: 1.5rem;
            border-radius: 1rem;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .card {
            border: none !important;
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }

        .btn-submit {
            background: var(--accent-gradient);
            border: none;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: opacity 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
            color: white;
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            color: white;
        }

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

        .text-danger {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .employee-checkbox {
            margin-right: 8px;
        }

        .employees-container {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ced4da;
            border-radius: 0.75rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .assigned-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mb-3">
        {{-- Modernized Header --}}
        <div class="page-header d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-pencil-square me-2"></i> 
                    Edit Project: <span class="text-primary">{{ $project->name }}</span>
                </h2>
                <p class="text-muted mb-0">Update project details and assign team members</p>
            </div>
            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-primary">
                <i class="bi bi-eye me-1"></i> View Project
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card m-auto" style="max-width: 800px;">
            <div class="card-body p-4 p-md-5">
                <h4 class="card-title text-center mb-4 text-secondary">Project Information</h4>

                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Project Name --}}
                    <div class="mb-4">
                        <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $project->name) }}" placeholder="Enter project name" required>
                        @error('name')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"
                            placeholder="Project description">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Dates Row --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label for="start_date" class="form-label">Start Date</label>
                            @php
                                $startDate = $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '';
                            @endphp
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date', $startDate) }}">
                            @error('start_date')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Deadline Date</label>
                            @php
                                $endDate = $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '';
                            @endphp
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date', $endDate) }}">
                            @error('end_date')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="not_started" {{ old('status', $project->status) == 'not_started' ? 'selected' : '' }}>Not Started</option>
                            <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Budget --}}
                    @if($project->budget)
                    <div class="mb-4">
                        <label for="budget" class="form-label">Budget</label>
                        <input type="number" name="budget" id="budget" class="form-control" step="0.01"
                            value="{{ old('budget', $project->budget) }}" placeholder="0.00">
                        @error('budget')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    {{-- Assign Employees Section --}}
                    <div class="mb-4">
                        <div class="section-title">
                            <i class="bi bi-people me-2"></i>Assign Team Members
                        </div>
                        <div class="employees-container">
                            @forelse($employees as $employee)
                                <div class="form-check">
                                    <input class="form-check-input employee-checkbox" type="checkbox" 
                                           name="assigned_employees[]" value="{{ $employee->id }}"
                                           id="employee_{{ $employee->id }}"
                                           {{ in_array($employee->id, old('assigned_employees', $assignedEmployees)) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex justify-content-between align-items-center" for="employee_{{ $employee->id }}">
                                        <span>
                                            {{ $employee->name }} 
                                            <small class="text-muted">({{ $employee->email }})</small>
                                        </span>
                                        @if(in_array($employee->id, $assignedEmployees))
                                            <span class="assigned-badge">Currently Assigned</span>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-people display-4"></i>
                                    <p class="mt-2 mb-0">No employees available</p>
                                </div>
                            @endforelse
                        </div>
                        @error('assigned_employees')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                        @error('assigned_employees.*')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                        
                        @if($employees->isNotEmpty())
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Select team members who will work on this project
                                </small>
                            </div>
                        @endif
                    </div>

                    {{-- Project Statistics --}}
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="section-title">
                            <i class="bi bi-graph-up me-2"></i>Project Statistics
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-end">
                                    <div class="h5 mb-1 text-primary">{{ $project->tasks->count() }}</div>
                                    <small class="text-muted">Total Tasks</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <div class="h5 mb-1 text-success">{{ $project->tasks->where('status', 'completed')->count() }}</div>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div>
                                    <div class="h5 mb-1 text-info">{{ $project->teamMembers->count() }}</div>
                                    <small class="text-muted">Team Members</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                        <div>
                            <a href="{{ route('projects.index') }}" class="btn btn-back me-2">
                                <i class="bi bi-arrow-left me-1"></i> Back to Projects
                            </a>
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-submit">
                                <i class="bi bi-save me-1"></i> Update Project
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Date validation
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function validateDates() {
                if (startDateInput.value && endDateInput.value) {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);
                    
                    if (endDate < startDate) {
                        endDateInput.setCustomValidity('End date cannot be before start date');
                    } else {
                        endDateInput.setCustomValidity('');
                    }
                }
            }

            startDateInput.addEventListener('change', validateDates);
            endDateInput.addEventListener('change', validateDates);

            // Select all employees functionality
            const selectAllCheckbox = document.createElement('div');
            selectAllCheckbox.className = 'form-check mb-2';
            selectAllCheckbox.innerHTML = `
                <input class="form-check-input" type="checkbox" id="selectAllEmployees">
                <label class="form-check-label fw-bold" for="selectAllEmployees">
                    Select All Employees
                </label>
            `;
            
            const employeesContainer = document.querySelector('.employees-container');
            if (employeesContainer) {
                employeesContainer.parentNode.insertBefore(selectAllCheckbox, employeesContainer);
                
                const selectAll = document.getElementById('selectAllEmployees');
                const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
                
                selectAll.addEventListener('change', function() {
                    employeeCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
                
                // Update select all when individual checkboxes change
                employeeCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(employeeCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(employeeCheckboxes).some(cb => cb.checked);
                        
                        selectAll.checked = allChecked;
                        selectAll.indeterminate = someChecked && !allChecked;
                    });
                });
            }
        });
    </script>
@endsection