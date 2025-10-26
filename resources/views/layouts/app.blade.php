<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') | Task Manager </title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-circle.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

    <style>
        /* General Layout */
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1f2937; /* Darker, modern slate color */
            --sidebar-hover: #374151;
            --primary-color: #4CAF50; /* Fresh Green */
            --light-bg: #f7f9fb; /* Clean background */
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        body {
            display: flex;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background-color: var(--light-bg);
            /* Prioritize a clean, modern font like Poppins or Noto Sans */
            font-family: 'Poppins', "Noto Sans", sans-serif !important; 
            color: #333;
        }

        /* Buttons */
        .btn {
            padding: .35rem .75rem !important; /* Slightly larger, more clickable */
            font-size: .9rem !important;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: white;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 1rem !important; /* Increased padding */
        }

        .sidebar .nav-link {
            color: #d1d5db; /* Light gray text for contrast */
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 10px; /* More vertical space */
            margin-bottom: 5px;
            border-bottom: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #ffffff; /* White text on hover */
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-color) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.4);
        }

        .sidebar .nav-link.active:hover {
            background-color: var(--primary-color) !important; /* Keep active color consistent */
        }

        .sidebar .nav-link .bi {
            font-size: 1.2rem;
            margin-right: 15px; /* More space between icon and text */
        }

        /* Content Area */
        .content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* Top Navigation Bar */
        .topnav {
            flex-shrink: 0;
            width: 100%;
            background-color: #ffffff;
            box-shadow: var(--shadow-light); /* Modern, soft shadow */
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
        }
        
        #currentDateTime {
            font-size: 0.95rem;
            color: #555;
            font-weight: 500;
        }

        .navbar-nav .nav-link {
            color: #333;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }
        
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: var(--shadow-light);
            border: none;
        }

        /* Card Styling */
        .card {
            border: 1px solid #e0e0e0; /* Subtle border for definition */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important; /* Lighter shadow */
            transition: all 0.3s ease;
        }
        
        /* Footer */
        footer {
            background-color: #ffffff;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.02);
            flex-shrink: 0;
        }
        
        footer .text-muted {
            font-size: 0.85rem;
            color: #777 !important;
        }

        main {
            flex-grow: 1;
        }
    </style>
</head>

<body>
    {{-- Sidebar: Modernized background and links --}}
    <div class="sidebar d-flex flex-column">
        <h4 class="mb-5 text-center">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center justify-content-center">
                {{-- Invert filter is removed, assuming the logo-circle-horizontal.png is now designed for dark background --}}
                <img src="{{ asset('assets/img/logo-circle-horizontal.png') }}" class="img-fluid" style="width: 80%; max-width: 180px; height: auto;"
                    alt="task manager">
            </a>
        </h4>
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i> Home
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ request()->is('mail*') ? 'active' : '' }}" href="{{ route('mail.inbox') }}">
                    <i class="bi bi-inbox"></i> Inbox
                </a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}"
                    href="{{ route('projects.index') }}">
                    <i class="bi bi-folder"></i> Employee List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('tasks*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                    <i class="bi bi-check2-square"></i> Tasks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('routines*') ? 'active' : '' }}"
                    href="{{ route('routines.index') }}">
                    <i class="bi bi-calendar-check"></i> Routines
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('notes*') ? 'active' : '' }}" href="{{ route('notes.index') }}">
                    <i class="bi bi-sticky"></i> Notes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('reminders*') ? 'active' : '' }}"
                    href="{{ route('reminders.index') }}">
                    <i class="bi bi-bell"></i> Reminders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('files*') ? 'active' : '' }}" href="{{ route('files.index') }}">
                    <i class="bi bi-file-earmark-text"></i> Files
                </a>
            </li>
        </ul>
        
        {{-- Added a space to push the list up slightly --}}
        <div style="height: 20px;"></div> 
    </div>
    
    <div class="content d-flex flex-column">
        {{-- Topnav: Clean white background, soft shadow --}}
        <header class="topnav">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid px-4">
                    {{-- Updated Brand/Date Display --}}
                    <a class="navbar-brand me-auto" href="{{ route('dashboard') }}">
                        <span id="currentDateTime"></span>
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-2" style="font-size: 1.2rem;"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    {{-- <li><a class="dropdown-item" href="#">Settings</a></li> --}}
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        
        {{-- Main Content: Added padding for internal content --}}
        <main class="p-4"> 
            @yield('content')
        </main>
        
        {{-- Footer: Subtle and clean --}}
        <footer class="mt-auto py-3 text-center">
            <div class="container-fluid">
                <span class="text-muted">&copy; {{ date('Y') }} Task Manager | Developed by <a href="#" style="color: var(--primary-color); text-decoration: none;">Admin & IT Department</a></span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit'
            };
            const dateTimeString = now.toLocaleDateString(undefined, options);
            document.getElementById('currentDateTime').innerText = dateTimeString;
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>

</html>