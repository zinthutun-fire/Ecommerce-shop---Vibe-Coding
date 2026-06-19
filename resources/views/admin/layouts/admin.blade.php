<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - ShopHub')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.0/css/dataTables.tailwindcss.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:block">
            <div class="p-4">
                <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="ShopHub" class="h-8"></a>
                <p class="text-xs text-gray-400">Admin Panel</p>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i> <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="fas fa-box w-5"></i> <span class="ml-3">Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="fas fa-folder w-5"></i> <span class="ml-3">Categories</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="fas fa-shopping-bag w-5"></i> <span class="ml-3">Orders</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="fas fa-users w-5"></i> <span class="ml-3">Users</span>
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.coupons.*') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="fas fa-tag w-5"></i> <span class="ml-3">Coupons</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="fas fa-star w-5"></i> <span class="ml-3">Reviews</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.reports.*') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="fas fa-chart-bar w-5"></i> <span class="ml-3">Reports</span>
                </a>
                <hr class="my-4 border-gray-700">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-store w-5"></i> <span class="ml-3">View Store</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="px-4 py-3">
                    @csrf
                    <button type="submit" class="flex items-center text-gray-300 hover:text-white">
                        <i class="fas fa-sign-out-alt w-5"></i> <span class="ml-3">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow px-6 py-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold">@yield('header', 'Dashboard')</h1>
                <div class="flex items-center space-x-4">
                    <span>{{ Auth::user()->name }}</span>
                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="" class="w-8 h-8 rounded-full">
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.0/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts-extra')
</body>
</html>
