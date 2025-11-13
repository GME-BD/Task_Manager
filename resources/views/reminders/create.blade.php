@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 shadow-sm p-3 rounded bg-white">
            {{ isset($reminder) ? 'Edit Reminder' : 'Add Reminder' }}
        </h2>
        <div class="card border-0 shadow-sm m-auto" style="max-width: 600px;">
            <div class="card-body">
                <form action="{{ isset($reminder) ? route('reminders.update', $reminder->id) : route('reminders.store') }}" method="POST">
                    @csrf
                    @if(isset($reminder))
                        @method('PUT')
                    @endif
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" name="title" id="title" class="form-control" 
                               value="{{ old('title', $reminder->title ?? '') }}" required>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $reminder->description ?? '') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control" 
                                   value="{{ old('date', $reminder->date ?? '') }}"
                                   min="{{ date('Y-m-d') }}">
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" name="time" id="time" class="form-control" 
                                   value="{{ old('time', $reminder->time ?? '') }}">
                            @error('time')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority *</label>
                            <select name="priority" id="priority" class="form-select" required>
                                <option value="low" {{ old('priority', $reminder->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $reminder->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $reminder->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="reminder_type" class="form-label">Reminder Type *</label>
                            <select name="reminder_type" id="reminder_type" class="form-select" required>
                                <option value="one_time" {{ old('reminder_type', $reminder->reminder_type ?? '') == 'one_time' ? 'selected' : '' }}>One Time</option>
                                <option value="recurring" {{ old('reminder_type', $reminder->reminder_type ?? '') == 'recurring' ? 'selected' : '' }}>Recurring</option>
                            </select>
                            @error('reminder_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3" id="recurring_pattern_container" style="display: none;">
                        <label for="recurring_pattern" class="form-label">Recurring Pattern</label>
                        <select name="recurring_pattern" id="recurring_pattern" class="form-select">
                            <option value="daily" {{ old('recurring_pattern', $reminder->recurring_pattern ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('recurring_pattern', $reminder->recurring_pattern ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('recurring_pattern', $reminder->recurring_pattern ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                        @error('recurring_pattern')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        {{ isset($reminder) ? 'Update Reminder' : 'Add Reminder' }}
                    </button>
                    <a href="{{ route('reminders.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reminderType = document.getElementById('reminder_type');
            const recurringContainer = document.getElementById('recurring_pattern_container');
            
            function toggleRecurringPattern() {
                if (reminderType.value === 'recurring') {
                    recurringContainer.style.display = 'block';
                    document.getElementById('recurring_pattern').setAttribute('required', 'required');
                } else {
                    recurringContainer.style.display = 'none';
                    document.getElementById('recurring_pattern').removeAttribute('required');
                }
            }
            
            // Initial state
            toggleRecurringPattern();
            
            // On change
            reminderType.addEventListener('change', toggleRecurringPattern);
        });
    </script>
@endsection