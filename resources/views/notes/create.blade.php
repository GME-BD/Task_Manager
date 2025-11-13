@extends('layouts.app')
@section('title')
    Add Note
@endsection
@section('content')
<div class="container">
    <!-- Enhanced Header -->
    <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-4 rounded mb-4 border-start border-5 border-success">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Create New Note</h2>
            <p class="text-muted mb-0">Capture your thoughts, ideas, and reminders</p>
        </div>
        <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Notes
        </a>
    </div>

    <div class="card border-0 shadow-sm m-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <form action="{{ route('notes.store') }}" method="POST" id="noteForm">
                @csrf
                
                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Note Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title') }}" 
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
                              required>{{ old('content') }}</textarea>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted" id="charCount">0 characters</small>
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
                                   value="{{ old('date', date('Y-m-d')) }}">
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
                                   value="{{ old('time') }}">
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
                                           id="is_important" value="1" {{ old('is_important') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="is_important">
                                        <i class="bi bi-star-fill text-warning me-1"></i>Mark as Important
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="add_reminder" 
                                           id="add_reminder" value="1" {{ old('add_reminder') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="add_reminder">
                                        <i class="bi bi-bell text-primary me-1"></i>Set Reminder
                                    </label>
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
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearForm()">
                                <i class="bi bi-eraser me-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-plus-circle me-2"></i>Create Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
    }
    
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #28a745, #218838);
        border: none;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-1px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentTextarea = document.getElementById('content');
        const charCount = document.getElementById('charCount');
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time');
        
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
        updateCharCount(); // Initial count
        
        // Set default date to today if empty
        if (!dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }
        
        // Form validation
        document.getElementById('noteForm').addEventListener('submit', function(e) {
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
    
    function clearForm() {
        if (confirm('Are you sure you want to clear all fields?')) {
            document.getElementById('noteForm').reset();
            document.getElementById('date').value = new Date().toISOString().split('T')[0];
            document.getElementById('charCount').textContent = '0 characters';
            document.getElementById('charCount').className = 'text-muted';
        }
    }
    
    // Auto-save draft functionality (optional)
    let autoSaveTimeout;
    function autoSaveDraft() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const formData = {
                title: document.getElementById('title').value,
                content: document.getElementById('content').value,
                date: document.getElementById('date').value,
                time: document.getElementById('time').value,
                is_important: document.getElementById('is_important').checked,
                add_reminder: document.getElementById('add_reminder').checked
            };
            
            // Save to localStorage (you could implement more sophisticated draft saving)
            localStorage.setItem('noteDraft', JSON.stringify(formData));
            console.log('Draft auto-saved');
        }, 2000);
    }
    
    // Load draft if exists
    function loadDraft() {
        const draft = localStorage.getItem('noteDraft');
        if (draft) {
            if (confirm('Would you like to restore your previously saved draft?')) {
                const formData = JSON.parse(draft);
                document.getElementById('title').value = formData.title || '';
                document.getElementById('content').value = formData.content || '';
                document.getElementById('date').value = formData.date || new Date().toISOString().split('T')[0];
                document.getElementById('time').value = formData.time || '';
                document.getElementById('is_important').checked = formData.is_important || false;
                document.getElementById('add_reminder').checked = formData.add_reminder || false;
                
                // Update character count
                document.getElementById('charCount').textContent = `${formData.content?.length || 0} characters`;
            }
        }
    }
    
    // Clear draft on successful submission
    function clearDraft() {
        localStorage.removeItem('noteDraft');
    }
    
    // Initialize auto-save and draft loading
    document.addEventListener('DOMContentLoaded', function() {
        const formElements = document.querySelectorAll('#noteForm input, #noteForm textarea');
        formElements.forEach(element => {
            element.addEventListener('input', autoSaveDraft);
            element.addEventListener('change', autoSaveDraft);
        });
        
        loadDraft();
        
        // Clear draft when form is submitted
        document.getElementById('noteForm').addEventListener('submit', clearDraft);
    });
</script>
@endsection