<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Client') | Kréyatik Studio</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            transition: width 0.2s;
        }

        .sidebar-collapsed {
            width: 5rem;
        }

        .sidebar-expanded {
            width: 16rem;
        }

        @media (max-width: 768px) {
            .sidebar-expanded {
                width: 5rem;
            }

            .sidebar-expanded:hover {
                width: 16rem;
            }
        }

        .notification-badge {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            font-size: 0.65rem;
        }
    </style>

    @yield('head')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar sidebar-expanded bg-gray-800 text-white hidden md:block">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 bg-gray-900">
                    <div class="flex items-center">
                        <img src="{{ asset('images/Studiosansfond.png') }}" alt="Kréyatik Studio" class="h-8 w-auto mr-2">
                        <span class="text-xl font-bold sidebar-text">Kréyatik Studio</span>
                    </div>
                </div>

                <!-- Profil utilisateur -->
                <div class="flex items-center p-4 border-b border-gray-700">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ auth()->user()->name }}">
                    </div>
                    <div class="ml-3 sidebar-text">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">Client</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-4 flex-grow overflow-y-auto">
                    <ul class="px-2">
                        <li class="mb-1">
                            <a href="{{ route('client.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('client.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                                <span class="sidebar-text">Tableau de bord</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('client.profile.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('client.profile.*') ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                <i class="fas fa-user w-5 h-5 mr-3"></i>
                                <span class="sidebar-text">Mon profil</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('client.projects.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('client.projects.*') ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                <i class="fas fa-project-diagram w-5 h-5 mr-3"></i>
                                <span class="sidebar-text">Mes projets</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('client.tickets.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('client.tickets.*') ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                <i class="fas fa-bug w-5 h-5 mr-3"></i>
                                <span class="sidebar-text">Mes tickets</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="/" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700">
                                <i class="fas fa-home w-5 h-5 mr-3"></i>
                                <span class="sidebar-text">Retour au site</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-700">
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700">
                            <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                            <span class="sidebar-text">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-4 md:px-6">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    <!-- Sidebar toggle for desktop -->
                    <button id="sidebar-toggle" class="hidden md:block text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    <h1 class="text-xl font-bold text-gray-800 md:ml-4">@yield('page_title', 'Espace Client')</h1>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center focus:outline-none">
                            <span class="hidden md:block mr-2 text-sm text-gray-700">{{ auth()->user()->name }}</span>
                            <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ auth()->user()->name }}">
                        </button>

                        <!-- Dropdown menu -->
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('client.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                            <div class="border-t border-gray-100"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile Sidebar (hidden by default) -->
            <div id="mobile-sidebar" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-40 hidden md:hidden">
                <div class="relative pt-4 pb-3 h-full overflow-y-auto bg-gray-800 w-64">
                    <div class="flex items-center justify-between px-4">
                        <div class="flex items-center">
                            <img src="{{ asset('images/Studiosansfond.png') }}" alt="Kréyatik Studio" class="h-8 w-auto mr-2">
                            <span class="text-xl font-bold text-white">Kréyatik Studio</span>
                        </div>
                        <button id="close-mobile-menu" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <div class="flex items-center p-4 border-b border-gray-700">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ auth()->user()->name }}">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">Client</p>
                        </div>
                    </div>

                    <nav class="mt-4">
                        <ul class="px-2">
                            <li class="mb-1">
                                <a href="{{ route('client.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('client.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                                    <span>Tableau de bord</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('client.profile.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('client.profile.*') ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                    <i class="fas fa-user w-5 h-5 mr-3"></i>
                                    <span>Mon profil</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('client.projects.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('client.projects.*') ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                    <i class="fas fa-project-diagram w-5 h-5 mr-3"></i>
                                    <span>Mes projets</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('client.tickets.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('client.tickets.*') ? 'bg-gray-900 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                                    <i class="fas fa-bug w-5 h-5 mr-3"></i>
                                    <span>Mes tickets</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('home') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700">
                                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                                    <span>Retour au site</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="p-4 mt-6 border-t border-gray-700">
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700">
                                <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <!-- Flash messages -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content_body')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle for desktop
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('sidebar-collapsed');
                    sidebar.classList.toggle('sidebar-expanded');

                    sidebarTexts.forEach(text => {
                        text.classList.toggle('hidden');
                    });
                });
            }

            // Mobile menu
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeMobileMenu = document.getElementById('close-mobile-menu');

            if (mobileMenuButton && mobileSidebar && closeMobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileSidebar.classList.toggle('hidden');
                });

                closeMobileMenu.addEventListener('click', function() {
                    mobileSidebar.classList.add('hidden');
                });
            }

            // User dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @yield('custom_js')
</body>
</html>