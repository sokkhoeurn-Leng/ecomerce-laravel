<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/admin/app.js'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen">
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-gray-900 border-r border-gray-800 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cube text-gray-900 text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg">Admin Panel</h1>
                    <p class="text-xs text-gray-500">Management System</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500/10 text-amber-500' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-200' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" 
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500/10 text-amber-500' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-200' }}">
                <i class="fas fa-tags w-5 text-center"></i>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-amber-500/10 text-amber-500' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-200' }}">
                <i class="fas fa-box w-5 text-center"></i>
                <span>Products</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" 
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-amber-500/10 text-amber-500' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-200' }}">
                <i class="fas fa-shopping-cart w-5 text-center"></i>
                <span>Orders</span>
            </a>

            <a href="{{ route('admin.users.index') }}" 
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-amber-500/10 text-amber-500' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-200' }}">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Users</span>
            </a>

            <div class="pt-4 mt-4 border-t border-gray-800">
                <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-gray-200 transition-colors">
                    <i class="fas fa-arrow-left w-5 text-center"></i>
                    <span>Back to Site</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
        <!-- Top Bar -->
        <header class="sticky top-0 z-30 bg-gray-950/80 backdrop-blur-md border-b border-gray-800">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold">@yield('page-title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-4">
                    <!-- User Menu -->
                    <div class="relative" id="user-menu-container">
                        <button onclick="toggleUserMenu()" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                            <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-gray-900">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <span class="hidden sm:block text-sm">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>

                        <div id="user-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-gray-900 border border-gray-800 rounded-xl shadow-xl py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-800">
                                <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            @if (Route::has('profile.edit'))
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white">
                                    <i class="fas fa-user w-4"></i>
                                    Profile
                                </a>
                            @endif
                            @if (Route::has('logout'))
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-gray-800 hover:text-red-300 w-full text-left">
                                        <i class="fas fa-sign-out-alt w-4"></i>
                                        Logout
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-6">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleUserMenu() {
            document.getElementById('user-menu').classList.toggle('hidden');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function(e) {
            const container = document.getElementById('user-menu-container');
            if (container && !container.contains(e.target)) {
                document.getElementById('user-menu').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
