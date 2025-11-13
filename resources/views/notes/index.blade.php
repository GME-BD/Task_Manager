@extends('layouts.app')
@section('title')
    Notes
@endsection
@section('content')
<div class="container">
    <!-- Enhanced Header -->
    <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-4 rounded mb-4 border-start border-5 border-success">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Notes</h2>
            <p class="text-muted mb-0">Manage your personal notes and reminders</p>
        </div>
        <a href="{{ route('notes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add Note
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ count($notes) }}</h4>
                            <p class="mb-0">Total Notes</p>
                        </div>
                        <i class="bi bi-journal-text fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $todayNotesCount ?? 0 }}</h4>
                            <p class="mb-0">Today's Notes</p>
                        </div>
                        <i class="bi bi-calendar-day fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $recentNotesCount ?? 0 }}</h4>
                            <p class="mb-0">Recent (7 days)</p>
                        </div>
                        <i class="bi bi-clock-history fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $importantNotesCount ?? 0 }}</h4>
                            <p class="mb-0">Important</p>
                        </div>
                        <i class="bi bi-star fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Search notes by title or content...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="dateFilter">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="recent">Recent</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="sortFilter">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="title_asc">Title A-Z</option>
                        <option value="title_desc">Title Z-A</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Grid -->
    <div class="row" id="notesContainer">
        @forelse($notes as $note)
            @php
                $noteDate = \Carbon\Carbon::parse($note->date);
                $isToday = $noteDate->isToday();
                $isRecent = $noteDate->gt(now()->subDays(7));
                $isImportant = $note->is_important ?? false;
            @endphp
            
            <div class="col-md-6 col-lg-4 mb-4 note-card" 
                 data-title="{{ strtolower($note->title) }}"
                 data-content="{{ strtolower($note->content) }}"
                 data-date="{{ $note->date }}"
                 data-important="{{ $isImportant ? 'true' : 'false' }}"
                 data-recent="{{ $isRecent ? 'true' : 'false' }}">
                <div class="card border-0 shadow-sm h-100 note-item {{ $isToday ? 'border-success' : '' }}">
                    <div class="card-header bg-transparent border-bottom-0 pt-3 pb-0">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                @if($isToday)
                                    <span class="badge bg-success mb-2">Today</span>
                                @elseif($isRecent)
                                    <span class="badge bg-info mb-2">Recent</span>
                                @endif
                                @if($isImportant)
                                    <span class="badge bg-warning mb-2"><i class="bi bi-star-fill me-1"></i>Important</span>
                                @endif
                                <h5 class="card-title fw-bold text-dark mb-1">{{ $note->title }}</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary border-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('notes.edit', $note->id) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#noteModal{{ $note->id }}"><i class="bi bi-eye me-2"></i>View Full</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this note?');">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <p class="card-text text-muted mb-3">{{ Str::limit($note->content, 120) }}</p>
                        
                        <div class="note-meta">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-calendar-date text-success me-2"></i>
                                <span class="small">
                                    {{ $noteDate->format('M j, Y') }}
                                    @if($isToday)
                                        <span class="text-success">(Today)</span>
                                    @endif
                                </span>
                            </div>
                            @if($note->time)
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock text-success me-2"></i>
                                    <span class="small">{{ \Carbon\Carbon::parse($note->time)->format('g:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Created {{ $noteDate->diffForHumans() }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-outline-success" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this note?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Note Modal -->
            <div class="modal fade" id="noteModal{{ $note->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $note->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="bi bi-calendar-date me-2"></i>Date:</strong> {{ $noteDate->format('F j, Y') }}
                                </div>
                                @if($note->time)
                                <div class="col-md-6">
                                    <strong><i class="bi bi-clock me-2"></i>Time:</strong> {{ \Carbon\Carbon::parse($note->time)->format('g:i A') }}
                                </div>
                                @endif
                            </div>
                            <div class="border-top pt-3">
                                <strong>Content:</strong>
                                <p class="mt-2">{{ $note->content }}</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-journal-x text-muted fs-1"></i>
                        <h4 class="text-muted mt-3">No Notes Found</h4>
                        <p class="text-muted">Start organizing your thoughts by creating your first note</p>
                        <a href="{{ route('notes.create') }}" class="btn btn-success mt-2">
                            <i class="bi bi-plus-circle me-2"></i>Create Your First Note
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    {{-- @if($notes->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $notes->links() }}
        </div>
    @endif --}}
</div>

<style>
    .note-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .note-card:hover {
        transform: translateY(-5px);
    }
    
    .note-item {
        border-left: 4px solid #28a745;
    }
    
    .note-meta {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
    }
    
    .card-footer {
        background: transparent !important;
    }
    
    .dropdown-toggle::after {
        display: none;
    }
    
    .bg-success {
        background: linear-gradient(135deg, #28a745, #218838) !important;
    }
    
    .input-group-text {
        background-color: #f8f9fa !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const dateFilter = document.getElementById('dateFilter');
        const sortFilter = document.getElementById('sortFilter');
        const noteCards = document.querySelectorAll('.note-card');
        
        function filterNotes() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedDate = dateFilter.value;
            const selectedSort = sortFilter.value;
            
            noteCards.forEach(card => {
                const title = card.getAttribute('data-title');
                const content = card.getAttribute('data-content');
                const date = card.getAttribute('data-date');
                const isImportant = card.getAttribute('data-important') === 'true';
                const isRecent = card.getAttribute('data-recent') === 'true';
                
                const matchesSearch = !searchTerm || 
                    title.includes(searchTerm) || 
                    content.includes(searchTerm);
                
                let matchesDate = true;
                if (selectedDate === 'today') {
                    const noteDate = new Date(date);
                    const today = new Date();
                    matchesDate = noteDate.toDateString() === today.toDateString();
                } else if (selectedDate === 'recent') {
                    matchesDate = isRecent;
                }
                
                if (matchesSearch && matchesDate) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterNotes);
        dateFilter.addEventListener('change', filterNotes);
        sortFilter.addEventListener('change', filterNotes);
        
        // Initialize filter
        filterNotes();
    });
</script>
@endsection