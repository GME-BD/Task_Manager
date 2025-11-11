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
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

    <style>
        /* Modern Color Scheme */
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: linear-gradient(180deg, #1a237e 0%, #283593 50%, #303f9f 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --primary-color: #4CAF50;
            --primary-gradient: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            --light-bg: #f8fafc;
            --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            --sidebar-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            --accent-color: #5c6bc0;
        }

        body {
            display: flex;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background-color: var(--light-bg);
            font-family: 'Poppins', "Noto Sans", sans-serif !important;
            color: #333;
        }

        /* Buttons */
        .btn {
            padding: .5rem 1rem !important;
            font-size: .9rem !important;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        /* Sidebar Styling - Modernized */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: white;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--sidebar-shadow);
            padding: 1.5rem 1.2rem !important;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
            opacity: 0;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 14px 16px;
            margin-bottom: 6px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(5px);
        }

        .sidebar .nav-link:hover::before {
            transform: scaleY(1);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }

        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .sidebar .nav-link .bi {
            font-size: 1.25rem;
            margin-right: 15px;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link:hover .bi {
            transform: scale(1.1);
        }

        /* Content Area */
        .content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            background: var(--light-bg);
            transition: all 0.3s ease;
        }

        .content.expanded {
            margin-left: 0;
        }

        /* Top Navigation Bar - Modernized */
        .topnav {
            flex-shrink: 0;
            width: 100%;
            background: linear-gradient(90deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--accent-color) !important;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }

        #currentDateTime {
            font-size: 1rem;
            color: #5c6bc0;
            font-weight: 500;
            background: rgba(92, 107, 192, 0.1);
            padding: 6px 12px;
            border-radius: 20px;
        }

        .navbar-nav .nav-link {
            color: #555;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }

        .dropdown-menu {
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: none;
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(76, 175, 80, 0.1);
        }

        /* Card Styling - Modernized */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow) !important;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12) !important;
        }

        /* Footer - Modernized */
        footer {
            background: linear-gradient(90deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.03);
            flex-shrink: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        footer .text-muted {
            font-size: 0.85rem;
            color: #777 !important;
        }

        main {
            flex-grow: 1;
            padding: 2rem !important;
        }

        /* Logo Styling */
        .logo-container {
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .logo-container:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .logo-img {
            width: 100%;
            max-width: 200px;
            height: auto;
            filter: brightness(0) invert(1);
        }

        /* User dropdown avatar */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 10px;
        }

        /* Animation for page load */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sidebar, .topnav, main, footer {
            animation: fadeIn 0.5s ease-out;
        }

        /* Hamburger Menu Button */
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--accent-color);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-right: 15px;
        }

        .sidebar-toggle:hover {
            background: rgba(92, 107, 192, 0.1);
            transform: scale(1.1);
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 240px;
            }
            
            .sidebar {
                padding: 1rem 0.8rem !important;
                position: fixed;
                height: 100vh;
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
                opacity: 1;
            }
            
            .content {
                margin-left: 0 !important;
            }
            
            main {
                padding: 1.5rem !important;
            }
            
            .sidebar-toggle {
                display: block !important;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none !important;
            }
            
            .sidebar-overlay {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    {{-- Mobile Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Sidebar: Modernized with gradient background --}}
    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="logo-container text-center">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center justify-content-center">
                <img src="{{ asset('assets/img/logo-circle-horizontal.png') }}" class="logo-img" alt="task manager">
            </a>
        </div>
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}"
                    href="{{ route('projects.index') }}">
                    <i class="bi bi-folder"></i> Project List
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
        </ul>

        {{-- Added a space to push the list up slightly --}}
        <div style="height: 20px;"></div>
    </div>

    <div class="content d-flex flex-column" id="mainContent">
        {{-- Topnav: Clean with subtle gradient --}}
        <header class="topnav">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid px-4">
                    {{-- Hamburger Menu Button --}}
                    <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

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
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-avatar">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                            @csrf
                                            <button type="submit" class="dropdown-item d-flex align-items-center">
                                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                                            </button>
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

        {{-- Footer: Modern and clean --}}
        <footer class="mt-auto py-3 text-center">
            <div class="container-fluid">
                <span class="text-muted">&copy; {{ date('Y') }} Task Manager | Developed by <a href="#"
                        style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Admin & IT Department</a></span>
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

        // Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');

            function toggleSidebar() {
                sidebar.classList.toggle('mobile-open');
                sidebarOverlay.classList.toggle('active');
            }

            // Toggle sidebar when hamburger button is clicked
            sidebarToggle.addEventListener('click', toggleSidebar);

            // Close sidebar when overlay is clicked
            sidebarOverlay.addEventListener('click', toggleSidebar);

            // Close sidebar when a nav link is clicked (on mobile)
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        toggleSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });
    </script>
</body>

</html>