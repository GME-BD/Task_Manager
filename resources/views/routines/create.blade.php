@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Enhanced Header -->
        <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-4 rounded mb-4 border-start border-5 border-primary">
            <div>
                <h2 class="mb-1 fw-bold text-dark">Create New Routine</h2>
                <p class="text-muted mb-0">Set up your daily, weekly, or monthly routine</p>
            </div>
            <a href="{{ route('routines.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Routines
            </a>
        </div>

        <div class="card border-0 shadow-sm m-auto" style="max-width: 700px;">
            <div class="card-body p-4">
                <form action="{{ route('routines.store') }}" method="POST" id="routineForm">
                    @csrf
                    
                    <!-- Title & Description -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label fw-semibold">Routine Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" placeholder="Enter routine title" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Describe your routine (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Frequency Selection -->
                    <div class="mb-4">
                        <label for="frequency" class="form-label fw-semibold">Frequency <span class="text-danger">*</span></label>
                        <select name="frequency" id="frequency" class="form-select @error('frequency') is-invalid @enderror" required>
                            <option value="">Select Frequency</option>
                            <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                        @error('frequency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dynamic Frequency Options -->
                    <div class="mb-4" id="frequency-options">
                        <!-- Days Selection -->
                        <div class="frequency-option" id="days-option" style="display: none;">
                            <label class="form-label fw-semibold">Select Days <span class="text-danger">*</span></label>
                            <div class="row">
                                @php
                                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                    $oldDays = old('days', []);
                                @endphp
                                @foreach ($days as $day)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="{{ $day }}" 
                                                   id="{{ $day }}" {{ in_array($day, $oldDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $day }}">
                                                {{ ucfirst($day) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('days')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Weeks Selection -->
                        <div class="frequency-option" id="weeks-option" style="display: none;">
                            <label class="form-label fw-semibold">Select Weeks <span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <button type="button" class="btn btn-outline-primary btn-sm me-2" id="select-all-weeks">Select All</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-all-weeks">Clear All</button>
                            </div>
                            <div class="row" style="max-height: 200px; overflow-y: auto;">
                                @php
                                    $oldWeeks = old('weeks', []);
                                @endphp
                                @for ($i = 1; $i <= 52; $i++)
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input week-checkbox" type="checkbox" name="weeks[]" 
                                                   value="{{ $i }}" id="week{{ $i }}" {{ in_array($i, $oldWeeks) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="week{{ $i }}">Week {{ $i }}</label>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            @error('weeks')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Months Selection -->
                        <div class="frequency-option" id="months-option" style="display: none;">
                            <label class="form-label fw-semibold">Select Months <span class="text-danger">*</span></label>
                            <div class="row">
                                @php
                                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    $oldMonths = old('months', []);
                                @endphp
                                @foreach ($months as $index => $month)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="months[]" 
                                                   value="{{ $index + 1 }}" id="month{{ $index + 1 }}" {{ in_array($index + 1, $oldMonths) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="month{{ $index + 1 }}">{{ $month }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('months')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Time Selection -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="start_time" class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                                   value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                                   value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('routines.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle me-2"></i>Create Routine
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .frequency-option {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 10px;
            border-left: 4px solid #4361ee;
        }
        
        .form-check-input:checked {
            background-color: #4361ee;
            border-color: #4361ee;
        }
        
        .card {
            border: none;
            border-radius: 12px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const frequencyElement = document.getElementById('frequency');
            const daysOption = document.getElementById('days-option');
            const weeksOption = document.getElementById('weeks-option');
            const monthsOption = document.getElementById('months-option');
            const selectAllWeeks = document.getElementById('select-all-weeks');
            const clearAllWeeks = document.getElementById('clear-all-weeks');
            const weekCheckboxes = document.querySelectorAll('.week-checkbox');

            function updateFrequencyOptions() {
                const value = frequencyElement.value;
                
                // Hide all options first
                daysOption.style.display = 'none';
                weeksOption.style.display = 'none';
                monthsOption.style.display = 'none';

                // Show selected option
                if (value === 'daily') {
                    daysOption.style.display = 'block';
                } else if (value === 'weekly') {
                    weeksOption.style.display = 'block';
                } else if (value === 'monthly') {
                    monthsOption.style.display = 'block';
                }
            }

            // Select All/Clear All for weeks
            selectAllWeeks?.addEventListener('click', function() {
                weekCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            });

            clearAllWeeks?.addEventListener('click', function() {
                weekCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });

            // Form validation
            document.getElementById('routineForm')?.addEventListener('submit', function(e) {
                const frequency = frequencyElement.value;
                let isValid = true;

                // Validate frequency-specific fields
                if (frequency === 'daily') {
                    const dayChecked = document.querySelectorAll('input[name="days[]"]:checked').length > 0;
                    if (!dayChecked) {
                        alert('Please select at least one day for daily routine.');
                        isValid = false;
                    }
                } else if (frequency === 'weekly') {
                    const weekChecked = document.querySelectorAll('input[name="weeks[]"]:checked').length > 0;
                    if (!weekChecked) {
                        alert('Please select at least one week for weekly routine.');
                        isValid = false;
                    }
                } else if (frequency === 'monthly') {
                    const monthChecked = document.querySelectorAll('input[name="months[]"]:checked').length > 0;
                    if (!monthChecked) {
                        alert('Please select at least one month for monthly routine.');
                        isValid = false;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Initialize on load
            frequencyElement.addEventListener('change', updateFrequencyOptions);
            updateFrequencyOptions(); // Set initial visibility based on old input or default
        });
    </script>
@endsection