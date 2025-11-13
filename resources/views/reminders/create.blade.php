@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-gradient">
            {{ isset($reminder) ? '✏️ Edit Reminder' : '🕒 Add New Reminder' }}
        </h2>
        <p class="text-muted">Set your reminders efficiently and never miss an important task.</p>
    </div>

    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 650px;">
        <div class="card-body p-4">
            <form action="{{ isset($reminder) ? route('reminders.update', $reminder->id) : route('reminders.store') }}" method="POST" novalidate>
                @csrf
                @if(isset($reminder))
                    @method('PUT')
                @endif

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control form-control-lg rounded-3"
                        placeholder="Enter reminder title"
                        value="{{ old('title', $reminder->title ?? '') }}" required>
                    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="form-control rounded-3"
                        placeholder="Write short details...">{{ old('description', $reminder->description ?? '') }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Date & Time -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label fw-semibold">Date</label>
                        <input type="date" name="date" id="date" class="form-control rounded-3"
                            value="{{ old('date', $reminder->date ?? '') }}"
                            min="{{ date('Y-m-d') }}">
                        @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="time" class="form-label fw-semibold">Time</label>
                        <input type="time" name="time" id="time" class="form-control rounded-3"
                            value="{{ old('time', $reminder->time ?? '') }}">
                        @error('time') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <!-- Priority & Type -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="priority" class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                        <select name="priority" id="priority" class="form-select rounded-3" required>
                            <option value="low" {{ old('priority', $reminder->priority ?? '') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                            <option value="medium" {{ old('priority', $reminder->priority ?? '') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="high" {{ old('priority', $reminder->priority ?? '') == 'high' ? 'selected' : '' }}>🔴 High</option>
                        </select>
                        @error('priority') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="reminder_type" class="form-label fw-semibold">Reminder Type <span class="text-danger">*</span></label>
                        <select name="reminder_type" id="reminder_type" class="form-select rounded-3" required>
                            <option value="one_time" {{ old('reminder_type', $reminder->reminder_type ?? '') == 'one_time' ? 'selected' : '' }}>One Time</option>
                            <option value="recurring" {{ old('reminder_type', $reminder->reminder_type ?? '') == 'recurring' ? 'selected' : '' }}>Recurring</option>
                        </select>
                        @error('reminder_type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <!-- Recurring Pattern -->
                <div class="mb-3" id="recurring_pattern_container" style="display: none;">
                    <label for="recurring_pattern" class="form-label fw-semibold">Recurring Pattern</label>
                    <select name="recurring_pattern" id="recurring_pattern" class="form-select rounded-3">
                        <option value="daily" {{ old('recurring_pattern', $reminder->recurring_pattern ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('recurring_pattern', $reminder->recurring_pattern ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('recurring_pattern', $reminder->recurring_pattern ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('recurring_pattern') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('reminders.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm submit-btn">
                        {{ isset($reminder) ? '💾 Update Reminder' : '➕ Add Reminder' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .text-gradient {
        background: linear-gradient(90deg, #007bff, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .form-control, .form-select {
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 6px rgba(0, 123, 255, 0.25);
    }

    .card {
        background: #ffffff;
        border-radius: 16px;
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .submit-btn:hover {
        background: linear-gradient(90deg, #0062ff, #5b00f5);
        border-color: transparent;
    }
</style>

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

        toggleRecurringPattern();
        reminderType.addEventListener('change', toggleRecurringPattern);
    });
</script>
@endsection
