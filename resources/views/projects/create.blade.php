@extends('layouts.app')

@section('title')
    Create New Project 🚀
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
            text-align: center;
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
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-2px);
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
        }
    </style>
@endsection

@section('content')
    <div class="container mb-3">
        <h2 class="page-header mb-5">
            <i class="bi bi-folder-plus me-2"></i> Create New Project
        </h2>

        <div class="card m-auto" style="max-width: 600px;">
            <div class="card-body p-4 p-md-5">

                <h4 class="card-title text-center mb-4 text-secondary">Project Details</h4>

                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf

                    {{-- Project Name --}}
                    <div class="mb-4">
                        <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        {{-- <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label> --}}
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Enter project name" required>
                        @error('name')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"
                            placeholder="Project description">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Dates Row --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date') }}">
                            @error('start_date')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Deadline Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="not_started" {{ old('status') == 'not_started' ? 'selected' : '' }}>Not Started
                            </option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Budget Field --}}
                    {{-- <div class="mb-4">
                        <label for="budget" class="form-label">Budget (Optional)</label>
                        <input type="number" name="budget" id="budget" class="form-control" step="0.01"
                            value="{{ old('budget') }}" placeholder="0.00">
                        @error('budget')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div> --}}

                 
                    {{-- Assign Employees --}}
                    <div class="mb-4">
                        <label class="form-label">Assign to Employees</label>
                        <div class="employees-container">
                            @foreach($employees as $employee)
                                <div class="form-check">
                                    <input class="form-check-input employee-checkbox" type="checkbox" name="users[]"
                                        value="{{ $employee->id }}" id="employee_{{ $employee->id }}" {{ in_array($employee->id, old('users', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="employee_{{ $employee->id }}">
                                        {{ $employee->name }} ({{ $employee->email }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('users')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                        @error('users.*')
                            <span class="text-danger mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mt-5">
                        <a href="{{ route('projects.index') }}" class="btn btn-back">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle me-1"></i> Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection