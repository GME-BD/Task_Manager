@extends('layouts.app')

@section('title')
    Projects 🚀
@endsection

{{-- Include Bootstrap Icons for the new buttons if not already in layouts.app --}}
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
            height: 100%; /* Important for row alignment */
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

        .status-pending { background-color: #ffc107; color: #343a40; } /* Warning */
        .status-on_going { background-color: #17a2b8; color: white; } /* Info/Cyan */
        .status-completed { background-color: #28a745; color: white; } /* Success */

        /* Action buttons group */
        .action-group .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            margin-right: 0.25rem; /* Space between buttons */
            /* Ensure primary button uses the modern color */
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .action-group .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Add Person button with accent gradient */
        .btn-accent {
            background: var(--accent-gradient);
            border: none;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            border-radius: 0.75rem;
            transition: opacity 0.3s ease;
        }

        .btn-accent:hover {
            opacity: 0.9;
        }

        .deadline-text {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .text-danger {
             font-weight: 600;
        }

    </style>
@endsection

@section('content')
    <div class="container py-4">
        
        {{-- Header Section (Modernized) --}}
        <div class="page-header d-flex justify-content-between align-items-center mb-5">
            <h2 class="header-title">Project Overview</h2>
            {{-- Changed button text to be more relevant to the list (Projects) --}}
            <a href="{{ route('projects.create') }}" class="btn btn-accent shadow">
                <i class="bi bi-plus-circle me-1"></i> Add New Project
            </a>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Projects Grid --}}
        <div class="row">
            @forelse($projects as $project)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body d-flex flex-column">
                            {{-- Project Title --}}
                            <h5 class="card-title">{{ $project->name }}</h5>
                            
                            {{-- Description (Subtle text) --}}
                            <p class="card-text text-secondary mb-3">{{ Str::limit($project->description, 100) }}</p> 
                            
                            {{-- Status --}}
                            <p class="mb-2">
                                <strong>Status:</strong>
                                @php
                                    $statusClass = [
                                        'pending' => 'status-pending',
                                        'on_going' => 'status-on_going',
                                        'completed' => 'status-completed',
                                    ][$project->status] ?? 'badge bg-light text-dark';
                                    $statusText = [
                                        'pending' => 'Pending',
                                        'on_going' => 'In Progress',
                                        'completed' => 'Completed',
                                    ][$project->status] ?? 'Unknown';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </p>

                            {{-- Deadline --}}
                            <p class="deadline-text mb-4">
                                <strong>Deadline:</strong>
                                @if($project->end_date && $project->end_date->isFuture())
                                    <span class="text-primary">{{ $project->end_date->format('M d, Y') }} ({{ $project->end_date->diffForHumans() }})</span>
                                @else
                                    <span class="text-danger"> Deadline Passed <i class="bi bi-clock-history"></i></span>
                                @endif
                            </p>
                            
                            {{-- Action Buttons (Modern Icon-only) --}}
                            <div class="action-group mt-auto">
                                {{-- Tasks List --}}
                                <a href="{{ route('projects.tasks.index', $project->id) }}" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="View Tasks">
                                    <i class="bi bi-list-task"></i>
                                </a>
                                
                                {{-- View Project --}}
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                {{-- Edit Project --}}
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                {{-- Delete Project --}}
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the project: {{ $project->name }}?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        No Employee found. Start by adding a new Employee!
                    </div>
                </div>
            @endforelse
        </div>
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