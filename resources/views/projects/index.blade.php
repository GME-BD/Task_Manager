@extends('layouts.app')

@section('title')
    Projects 🚀
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
    .page-header {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .header-title { font-weight: 600; color: #343a40; }
    .card { border: none; border-radius: var(--card-border-radius); box-shadow: var(--card-shadow); transition: transform 0.3s ease, box-shadow 0.3s ease; height: 100%; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); }
    .card-body { padding: 1.5rem; }
    .card-title { font-size: 1.35rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--primary-dark); }
    .status-badge { font-weight: 600; padding: 0.35em 0.7em; border-radius: 0.5rem; font-size: 0.85rem; }
    .status-pending { background-color: #ffc107; color: #343a40; }
    .status-on_going { background-color: #17a2b8; color: white; }
    .status-completed { background-color: #28a745; color: white; }
    .action-group .btn { border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.9rem; margin-right: 0.25rem; background-color: var(--primary-color); border-color: var(--primary-color); }
    .action-group .btn-danger { background-color: #dc3545; border-color: #dc3545; }
    .btn-accent { background: var(--accent-gradient); border: none; font-weight: 500; padding: 0.5rem 1.5rem; border-radius: 0.75rem; transition: opacity 0.3s ease; }
    .btn-accent:hover { opacity: 0.9; }
    .deadline-text { font-size: 0.9rem; color: #6c757d; }
    .text-danger { font-weight: 600; }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="page-header d-flex justify-content-between align-items-center mb-5">
        <h2 class="header-title">Project Overview</h2>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('projects.create') }}" class="btn btn-accent shadow">
                <i class="bi bi-plus-circle me-1"></i> Add New Project
            </a>
        @endif
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
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text text-secondary mb-3">{{ Str::limit($project->description, 100) }}</p>

                    {{-- Status --}}
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
                    <p class="mb-2"><strong>Status:</strong> <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></p>

                    {{-- Deadline --}}
                    <p class="deadline-text mb-3">
                        <strong>Deadline:</strong>
                        @if($project->end_date && $project->end_date->isFuture())
                            <span class="text-primary">{{ $project->end_date->format('M d, Y') }} ({{ $project->end_date->diffForHumans() }})</span>
                        @else
                            <span class="text-danger">Deadline Passed <i class="bi bi-clock-history"></i></span>
                        @endif
                    </p>

                    {{-- Assigned Employees --}}
                    <p>
                        
                        <strong>Assigned Employees:</strong>
                        @forelse($project->users as $employee)
                            {{ $employee->name }}@if(!$loop->last), @endif
                        @empty
                            None
                        @endforelse

                    </p>

                    {{-- Action Buttons --}}
                    @if(Auth::user()->isAdmin())
                    <div class="action-group mt-auto">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary" data-bs-toggle="tooltip" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning" data-bs-toggle="tooltip" title="Edit Project">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this project?')" data-bs-toggle="tooltip" title="Delete Project">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                No projects found.
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
