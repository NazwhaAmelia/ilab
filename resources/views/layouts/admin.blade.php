<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - ElevOne-lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (min-width: 768px) {
            .admin-main-content {
                height: 100vh;
                overflow: hidden;
            }
            
            .admin-content-scroll {
                height: calc(100vh - 64px);
                overflow-y: auto;
            }
        }

        .sidebar-link {
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background: rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3b82f6;
        }

        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.2);
            border-left: 4px solid #3b82f6;
            font-weight: 600;
        }

        .photo-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="admin-main-content flex">
        {{-- Sidebar --}}
        <aside class="hidden md:block w-64 bg-white shadow-lg flex-shrink-0">
            <div class="h-screen flex flex-col">
                {{-- Logo --}}
                <div class="h-16 bg-gradient-to-r from-blue-600 to-blue-700 flex items-center justify-center">
                    <h1 class="text-white text-xl font-bold tracking-wide">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        ElevOne-lab
                    </h1>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 overflow-y-auto py-6">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line w-6"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.schedules.index') }}" 
                       class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt w-6"></i>
                        <span class="ml-3">Jadwal</span>
                    </a>

                    <a href="{{ route('admin.classrooms.index') }}" 
                       class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.classrooms.*') ? 'active' : '' }}">
                        <i class="fas fa-door-open w-6"></i>
                        <span class="ml-3">Ruang Kelas/Lab</span>
                    </a>

                    <a href="{{ route('admin.classes.index') }}" 
                       class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-6"></i>
                        <span class="ml-3">Kelas</span>
                    </a>

                    <a href="{{ route('admin.teachers.index') }}" 
                       class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher w-6"></i>
                        <span class="ml-3">Guru</span>
                    </a>

                    <a href="{{ route('admin.subjects.index') }}" 
                       class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                        <i class="fas fa-book w-6"></i>
                        <span class="ml-3">Mata Pelajaran</span>
                    </a>
                </nav>

                {{-- User Info --}}
                <div class="border-t border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                    </div>
                    <div class="mt-3 space-y-2">
                        <a href="{{ route('home') }}" 
                           class="flex items-center w-full px-3 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition"
                           target="_blank">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Lihat Halaman Utama
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            {{-- Mobile Header --}}
            <header class="md:hidden bg-white shadow-sm h-16 flex items-center justify-between px-4">
                <h1 class="text-lg font-bold text-blue-600">ElevOne-lab</h1>
                <button id="mobile-menu-btn" class="text-gray-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </header>

            {{-- Page Header --}}
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 flex-shrink-0">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    @if(isset($breadcrumb))
                        <p class="text-sm text-gray-500">{{ $breadcrumb }}</p>
                    @endif
                </div>
                @yield('header-actions')
            </header>

            {{-- Content Area --}}
            <main class="admin-content-scroll flex-1 p-8 overflow-y-auto">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm animate-pulse">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-xl"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Mobile Sidebar Overlay --}}
    <div id="mobile-sidebar" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <aside class="w-64 bg-white h-full shadow-lg">
            <div class="h-16 bg-gradient-to-r from-blue-600 to-blue-700 flex items-center justify-between px-4">
                <h1 class="text-white text-xl font-bold">ElevOne-lab</h1>
                <button id="close-mobile-menu" class="text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="py-6">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                    <i class="fas fa-chart-line w-6"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                    <i class="fas fa-calendar-alt w-6"></i>
                    <span class="ml-3">Jadwal</span>
                </a>
                <a href="{{ route('admin.classrooms.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                    <i class="fas fa-door-open w-6"></i>
                    <span class="ml-3">Ruang Kelas/Lab</span>
                </a>
                <a href="{{ route('admin.classes.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3">Kelas</span>
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                    <i class="fas fa-chalkboard-teacher w-6"></i>
                    <span class="ml-3">Guru</span>
                </a>
                <a href="{{ route('admin.subjects.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                    <i class="fas fa-book w-6"></i>
                    <span class="ml-3">Mata Pelajaran</span>
                </a>
            </nav>
        </aside>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const closeMobileMenu = document.getElementById('close-mobile-menu');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileSidebar.classList.remove('hidden');
            });
        }

        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', () => {
                mobileSidebar.classList.add('hidden');
            });
        }

        if (mobileSidebar) {
            mobileSidebar.addEventListener('click', (e) => {
                if (e.target === mobileSidebar) {
                    mobileSidebar.classList.add('hidden');
                }
            });
        }

        // Photo preview
        function previewPhoto(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="animate-pulse"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>

    @stack('scripts')
</body>
</html>
