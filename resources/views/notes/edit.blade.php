@extends('layouts.app')
@section('title')
    Edit Note
@endsection
@section('content')
<div class="container">
    <!-- Enhanced Header -->
    <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-4 rounded mb-4 border-start border-5 border-warning">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Edit Note</h2>
            <p class="text-muted mb-0">Update your note details and content</p>
        </div>
        <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Notes
        </a>
    </div>

    <div class="card border-0 shadow-sm m-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <form action="{{ route('notes.update', $note->id) }}" method="POST" id="editNoteForm">
                @csrf
                @method('PUT')
                
                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Note Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title', $note->title) }}" 
                           placeholder="Enter a descriptive title for your note"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Content -->
                <div class="mb-4">
                    <label for="content" class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                    <textarea name="content" id="content" 
                              class="form-control @error('content') is-invalid @enderror" 
                              rows="6" 
                              placeholder="Write your note content here..."
                              required>{{ old('content', $note->content) }}</textarea>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" id="charCount">{{ strlen($note->content) }} characters</small>
                        <small class="text-muted">Required</small>
                    </div>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-semibold">Date</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-calendar-date"></i>
                            </span>
                            <input type="date" name="date" id="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', $note->date) }}">
                        </div>
                        <small class="text-muted">Leave empty for today's date</small>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="time" class="form-label fw-semibold">Time</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-clock"></i>
                            </span>
                            <input type="time" name="time" id="time" 
                                   class="form-control @error('time') is-invalid @enderror" 
                                   value="{{ old('time', $note->time) }}">
                        </div>
                        <small class="text-muted">Optional time reminder</small>
                        @error('time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-gear me-2"></i>Additional Options</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_important" 
                                           id="is_important" value="1" {{ old('is_important', $note->is_important ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="is_important">
                                        <i class="bi bi-star-fill text-warning me-1"></i>Mark as Important
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="add_reminder" 
                                           id="add_reminder" value="1" {{ old('add_reminder', $note->add_reminder ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="add_reminder">
                                        <i class="bi bi-bell text-primary me-1"></i>Set Reminder
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Note Information -->
                <div class="card border-info bg-light mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3 text-info"><i class="bi bi-info-circle me-2"></i>Note Information</h6>
                        <div class="row small text-muted">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-calendar me-2"></i>
                                    <span>Created: {{ \Carbon\Carbon::parse($note->created_at)->format('M j, Y g:i A') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    <span>Last Updated: {{ \Carbon\Carbon::parse($note->updated_at)->format('M j, Y g:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setTodaysDate()">
                                <i class="bi bi-calendar-check me-1"></i>Today's Date
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setCurrentTime()">
                                <i class="bi bi-clock me-1"></i>Current Time
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="previewNote()">
                                <i class="bi bi-eye me-1"></i>Preview
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="resetForm()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Changes
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div>
                        <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>Delete
                        </button>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-check-circle me-2"></i>Update Note
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this note?</p>
                <div class="alert alert-warning">
                    <strong>"{{ $note->title }}"</strong><br>
                    <small class="text-muted">This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete Note</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Note Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4 id="previewTitle" class="mb-3"></h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="bi bi-calendar-date me-2"></i>Date:</strong> <span id="previewDate"></span>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-clock me-2"></i>Time:</strong> <span id="previewTime"></span>
                    </div>
                </div>
                <div class="border-top pt-3">
                    <strong>Content:</strong>
                    <p id="previewContent" class="mt-2" style="white-space: pre-wrap;"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
    }
    
    .form-control:focus {
        border-color: #fd7e14;
        box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #fd7e14, #e55a00);
        border: none;
        color: white;
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, #e55a00, #cc4c00);
        transform: translateY(-1px);
        color: white;
    }
    
    .border-warning {
        border-color: #fd7e14 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentTextarea = document.getElementById('content');
        const charCount = document.getElementById('charCount');
        const originalData = {
            title: document.getElementById('title').value,
            content: document.getElementById('content').value,
            date: document.getElementById('date').value,
            time: document.getElementById('time').value,
            is_important: document.getElementById('is_important').checked,
            add_reminder: document.getElementById('add_reminder').checked
        };
        
        // Character count for content
        function updateCharCount() {
            const length = contentTextarea.value.length;
            charCount.textContent = `${length} characters`;
            
            if (length > 1000) {
                charCount.className = 'text-warning';
            } else {
                charCount.className = 'text-muted';
            }
        }
        
        contentTextarea.addEventListener('input', updateCharCount);
        
        // Check for changes
        function hasChanges() {
            return (
                document.getElementById('title').value !== originalData.title ||
                document.getElementById('content').value !== originalData.content ||
                document.getElementById('date').value !== originalData.date ||
                document.getElementById('time').value !== originalData.time ||
                document.getElementById('is_important').checked !== originalData.is_important ||
                document.getElementById('add_reminder').checked !== originalData.add_reminder
            );
        }
        
        // Warn before leaving if there are unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (hasChanges()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        // Form submission
        document.getElementById('editNoteForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            
            if (!title) {
                e.preventDefault();
                alert('Please enter a title for your note.');
                document.getElementById('title').focus();
                return;
            }
            
            if (!content) {
                e.preventDefault();
                alert('Please enter content for your note.');
                document.getElementById('content').focus();
                return;
            }
        });
    });
    
    // Quick action functions
    function setTodaysDate() {
        document.getElementById('date').value = new Date().toISOString().split('T')[0];
    }
    
    function setCurrentTime() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        document.getElementById('time').value = `${hours}:${minutes}`;
    }
    
    function resetForm() {
        if (confirm('Are you sure you want to reset all changes?')) {
            document.getElementById('title').value = '{{ $note->title }}';
            document.getElementById('content').value = '{{ $note->content }}';
            document.getElementById('date').value = '{{ $note->date }}';
            document.getElementById('time').value = '{{ $note->time }}';
            document.getElementById('is_important').checked = {{ $note->is_important ? 'true' : 'false' }};
            document.getElementById('add_reminder').checked = {{ $note->add_reminder ? 'true' : 'false' }};
            
            // Update character count
            document.getElementById('charCount').textContent = '{{ strlen($note->content) }} characters';
            document.getElementById('charCount').className = 'text-muted';
        }
    }
    
    function previewNote() {
        document.getElementById('previewTitle').textContent = document.getElementById('title').value || 'No Title';
        document.getElementById('previewDate').textContent = document.getElementById('date').value || 'Not set';
        document.getElementById('previewTime').textContent = document.getElementById('time').value || 'Not set';
        document.getElementById('previewContent').textContent = document.getElementById('content').value || 'No content';
        
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    }
</script>
@endsection