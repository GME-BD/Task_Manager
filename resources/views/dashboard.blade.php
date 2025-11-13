@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #f8fafc;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f1f5f9;
            --card-radius: 20px;
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 30px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .dashboard-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--card-radius);
            box-shadow: var(--shadow-md);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            background: rgba(255, 255, 255, 0.3);
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            border-radius: var(--card-radius);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: var(--card-radius) var(--card-radius) 0 0;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--dark), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--dark);
            font-weight: 600;
            opacity: 0.8;
        }

        .welcome-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.05));
            backdrop-filter: blur(20px);
            border-radius: var(--card-radius);
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .section-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            border-radius: var(--card-radius);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .section-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .section-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .section-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .list-item {
            background: rgba(255, 255, 255, 0.6);
            border: none;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            padding: 1.25rem;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            backdrop-filter: blur(10px);
        }

        .list-item:hover {
            transform: translateX(8px);
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .list-item.today {
            border-left-color: var(--warning);
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(255, 255, 255, 0.6));
        }

        .list-item.upcoming {
            border-left-color: var(--success);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(255, 255, 255, 0.6));
        }

        .list-item.overdue {
            border-left-color: var(--danger);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(255, 255, 255, 0.6));
        }

        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.75rem;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--dark);
            opacity: 0.7;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-modern {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatElement 15s infinite linear;
        }

        @keyframes floatElement {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }

            100% {
                transform: translateY(0px) rotate(360deg);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea, #764ba2, #f093fb, #f5576c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 300% 300%;
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .progress-ring {
            width: 60px;
            height: 60px;
        }

        .progress-ring-circle {
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
            stroke-dasharray: 283;
            stroke-dashoffset: 283;
            transition: stroke-dashoffset 0.5s ease;
        }

        .quick-stats {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            border-radius: var(--card-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <div class="floating-elements">
            {{-- <div class="floating-element" style="width: 100px; height: 100px; top: 10%; left: 5%;"></div>
            <div class="floating-element" style="width: 150px; height: 150px; top: 60%; right: 10%;"></div>
            <div class="floating-element" style="width: 80px; height: 80px; bottom: 20%; left: 15%;"></div> --}}
        </div>

        <div class="container">
            {{-- Welcome Header --}}
            <div class="welcome-header glass-card mb-5">
                <div class="d-flex flex-column align-items-center justify-content-center text-center py-4">
                    <h1 class="display-6 fw-bold gradient-text mb-2">Welcome, {{ Auth::user()->name }}</h1>
                    <p class="mb-3 text-dark opacity-90">Your daily productivity dashboard</p>

                    {{-- <div class="date-time-section">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <div class="text-center">
                                <div class="h5 mb-0 text-dark">{{ now()->format('l') }}</div>
                                <div class="small text-muted">{{ now()->format('F j, Y') }}</div>
                            </div>
                            <div class="vr"></div>
                            <div class="text-center">
                                <div class="h5 mb-0 text-dark">{{ now()->format('g:i A') }}</div>
                                <div class="small text-muted">Current Time</div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            {{-- Quick Stats --}}
            {{-- <div class="quick-stats glass-card">
                <div class="row text-center">
                    <div class="col">
                        <div class="h4 text-black mb-1">{{ $tasksCount ?? 0 }}</div>
                        <small class="text-black opacity-75">Active Tasks</small>
                    </div>
                    <div class="col">
                        <div class="h4 text-black mb-1">{{ $projectsCount ?? 0 }}</div>
                        <small class="text-black opacity-75">Projects</small>
                    </div>
                    <div class="col">
                        <div class="h4 text-black mb-1">{{ $todayRoutines->count() ?? 0 }}</div>
                        <small class="text-black opacity-75">Today's Routines</small>
                    </div>
                    <div class="col">
                        <div class="h4 text-black mb-1">{{ $upcomingReminders->count() ?? 0 }}</div>
                        <small class="text-black opacity-75">Reminders</small>
                    </div>
                </div>
            </div> --}}

            {{-- Main Stats Grid --}}
            <div class="row mb-5">
                <div class="col-xl-2 col-md-4 col-6 mb-4">
                    <div class="stat-card h-100">
                        <div class="text-center">
                            <div class="stat-icon mx-auto">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                            <div class="stat-number">{{ $tasksCount ?? 0 }}</div>
                            <div class="stat-label">Tasks</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-4">
                    <div class="stat-card h-100">
                        <div class="text-center">
                            <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                <i class="bi bi-folder2-open"></i>
                            </div>
                            <div class="stat-number">{{ $projectsCount ?? 0 }}</div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-4">
                    <div class="stat-card h-100">
                        <div class="text-center">
                            <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <div class="stat-number">{{ $routinesCount ?? 0 }}</div>
                            <div class="stat-label">Routines</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-4">
                    <div class="stat-card h-100">
                        <div class="text-center">
                            <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="stat-number">{{ $notesCount ?? 0 }}</div>
                            <div class="stat-label">Notes</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-4">
                    <div class="stat-card h-100">
                        <div class="text-center">
                            <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #ff9a9e, #fecfef);">
                                <i class="bi bi-files"></i>
                            </div>
                            <div class="stat-number">{{ $filesCount ?? 0 }}</div>
                            <div class="stat-label">Files</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-4">
                    <div class="stat-card h-100">
                        <div class="text-center">
                            <div class="stat-icon mx-auto"
                                style="background: linear-gradient(135deg, #a8edea, #fed6e3); color: #333;">
                                <i class="bi bi-bell"></i>
                            </div>
                            <div class="stat-number">{{ $remindersCount ?? 0 }}</div>
                            <div class="stat-label">Reminders</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Grid --}}
            <div class="row g-4">
                {{-- Recent Tasks --}}
                <div class="col-xl-6 col-lg-12">
                    <div class="section-card h-100">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="bi bi-list-task me-2"></i>Recent Tasks
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @forelse($recentTasks ?? [] as $task)
                                @php
                                    // Convert string dates to Carbon instances for comparison
                                    $dueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                                    $isOverdue = $dueDate && $dueDate->isPast() && $task->status !== 'completed';
                                    $statusClass = $task->status == 'completed' ? 'upcoming' : ($isOverdue ? 'overdue' : 'today');
                                @endphp
                                <div class="list-item {{ $statusClass }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ Str::limit($task->title, 35) }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-folder me-1"></i>{{ $task->project->name ?? 'No Project' }}
                                                @if($dueDate)
                                                    • <i class="bi bi-calendar me-1"></i>{{ $dueDate->format('M d') }}
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge-modern">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="bi bi-check2-circle"></i>
                                    <p class="mb-3">No tasks yet</p>
                                    <a href="{{ route('tasks.create') }}" class="btn-modern btn-sm">Create Task</a>
                                </div>
                            @endforelse

                            @if(($recentTasks ?? collect())->count() > 0)
                                <div class="text-center mt-4">
                                    <a href="{{ route('tasks.index') ?? '#' }}" class="btn-modern btn-sm">
                                        View All Tasks
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Today's Routines --}}
                <div class="col-xl-6 col-lg-12">
                    <div class="section-card h-100">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="bi bi-arrow-repeat me-2"></i>Today's Routines
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @forelse($todayRoutines ?? [] as $routine)
                                @php
                                    // Convert time string to Carbon instance for formatting
                                    $startTime = $routine->start_time ? \Carbon\Carbon::parse($routine->start_time) : null;
                                @endphp
                                <div class="list-item today">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ Str::limit($routine->title, 35) }}</h6>
                                            <small class="text-muted">
                                                @if($startTime)
                                                    <i class="bi bi-clock me-1"></i>{{ $startTime->format('g:i A') }}
                                                @endif
                                                @if($routine->description)
                                                    • {{ Str::limit($routine->description, 25) }}
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge-modern"
                                            style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                                            {{ ucfirst($routine->frequency) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <p class="mb-3">No routines for today</p>
                                    <a href="{{ route('routines.create') }}" class="btn-modern btn-sm">Create Routine</a>
                                </div>
                            @endforelse

                            @if(($todayRoutines ?? collect())->count() > 0)
                                <div class="text-center mt-4">
                                    <a href="{{ route('routines.index') }}" class="btn-modern btn-sm">
                                        View All Routines
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Recent Projects --}}
                <div class="col-xl-6 col-lg-12">
                    <div class="section-card h-100">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="bi bi-folder2 me-2"></i>Recent Projects
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @forelse($recentProjects ?? [] as $project)
                                <div class="list-item upcoming">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ Str::limit($project->name, 35) }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>{{ $project->teamMembers->count() ?? 0 }} members
                                                • <i class="bi bi-list-task me-1"></i>{{ $project->tasks->count() ?? 0 }} tasks
                                            </small>
                                        </div>
                                        <span class="badge-modern"
                                            style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="bi bi-folder2"></i>
                                    <p class="mb-3">No projects yet</p>
                                    <a href="{{ route('projects.create') }}" class="btn-modern btn-sm">Create Project</a>
                                </div>
                            @endforelse

                            @if(($recentProjects ?? collect())->count() > 0)
                                <div class="text-center mt-4">
                                    <a href="{{ route('projects.index') }}" class="btn-modern btn-sm">
                                        View All Projects
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Upcoming Reminders --}}
                <div class="col-xl-6 col-lg-12">
                    <div class="section-card h-100">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="bi bi-bell me-2"></i>Upcoming Reminders
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @forelse($upcomingReminders ?? [] as $reminder)
                                @php
                                    // Convert string dates to Carbon instances
                                    $reminderDate = $reminder->date ? \Carbon\Carbon::parse($reminder->date) : null;
                                    $reminderTime = $reminder->time ? \Carbon\Carbon::parse($reminder->time) : null;
                                    
                                    // Determine status
                                    $isToday = $reminderDate && $reminderDate->isToday();
                                    $isPast = $reminderDate && $reminderDate->isPast();
                                    $statusClass = $isToday ? 'today' : ($isPast ? 'overdue' : 'upcoming');
                                    
                                    // Determine badge text and color
                                    $badgeText = $isToday ? 'Today' : ($isPast ? 'Overdue' : ($reminderDate ? $reminderDate->diffForHumans() : 'Scheduled'));
                                    $badgeColor = $isToday ? '#f59e0b' : ($isPast ? '#ef4444' : '#10b981');
                                @endphp
                                <div class="list-item {{ $statusClass }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ Str::limit($reminder->title, 35) }}</h6>
                                            <small class="text-muted">
                                                @if($reminderDate)
                                                    <i class="bi bi-calendar me-1"></i>{{ $reminderDate->format('M d, Y') }}
                                                @endif
                                                @if($reminderTime)
                                                    • <i class="bi bi-clock me-1"></i>{{ $reminderTime->format('g:i A') }}
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge-modern"
                                            style="background: linear-gradient(135deg, {{ $badgeColor }}, {{ $badgeColor }});">
                                            {{ $badgeText }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="bi bi-bell"></i>
                                    <p class="mb-3">No upcoming reminders</p>
                                    <a href="{{ route('reminders.create') }}" class="btn-modern btn-sm">Create Reminder</a>
                                </div>
                            @endforelse

                            @if(($upcomingReminders ?? collect())->count() > 0)
                                <div class="text-center mt-4">
                                    <a href="{{ route('reminders.index') }}" class="btn-modern btn-sm">
                                        View All Reminders
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function () {
            // Animate stats counting
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const target = parseInt(stat.textContent) || 0;
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        stat.textContent = target;
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(current);
                    }
                }, 30);
            });

            // Add parallax effect to floating elements
            window.addEventListener('scroll', function () {
                const scrolled = window.pageYOffset;
                const floatingElements = document.querySelectorAll('.floating-element');
                floatingElements.forEach((element, index) => {
                    const speed = 0.5 + (index * 0.1);
                    element.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.1}deg)`;
                });
            });
        });
    </script>
@endsection