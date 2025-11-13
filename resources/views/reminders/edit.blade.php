@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-gradient">✏️ Edit Reminder</h2>
        <p class="text-muted mb-0">Update your reminder details and stay on track effortlessly.</p>
    </div>

    <div class="card shadow-lg border-0 mx-auto glass-card" style="max-width: 650px;">
        <div class="card-body p-4">
            <form action="{{ route('reminders.update', $reminder->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control form-control-lg rounded-3"
                        value="{{ $reminder->title }}" required placeholder="Enter reminder title">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" id="description" class="form-control rounded-3" rows="4"
                        placeholder="Add some details...">{{ $reminder->description }}</textarea>
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Date & Time -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label fw-semibold">Date</label>
                        <input type="date" name="date" id="date" class="form-control rounded-3"
                            value="{{ $reminder->date }}">
                        @error('date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="time" class="form-label fw-semibold">Time</label>
                        <input type="time" name="time" id="time" class="form-control rounded-3"
                            value="{{ $reminder->time }}">
                        @error('time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('reminders.index') }}" class="btn btn-outline-secondary px-4 rounded-3">Cancel</a>
                    <button type="submit" class="btn btn-gradient px-4 py-2 rounded-3 shadow-sm">
                        💾 Update Reminder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Title Gradient */
    .text-gradient {
        background: linear-gradient(90deg, #007bff, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Card style */
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Inputs and Buttons */
    .form-control {
        transition: all 0.2s ease-in-out;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .btn-gradient {
        background: linear-gradient(90deg, #007bff, #6f42c1);
        border: none;
        color: #fff;
        font-weight: 500;
        transition: all 0.25s ease-in-out;
    }
    .btn-gradient:hover {
        background: linear-gradient(90deg, #0056d2, #5631a5);
        transform: translateY(-1px);
    }
</style>
@endsection
