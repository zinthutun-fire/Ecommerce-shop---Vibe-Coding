<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Ecommerce'))</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body id="app" class="font-sans antialiased bg-gray-50">
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="ShopHub" class="h-8"></a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-indigo-600">Shop</a>
                        <div class="relative group">
                            <button class="text-gray-600 hover:text-indigo-600">Categories <i class="fas fa-chevron-down text-xs"></i></button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                @php $categories = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->get(); @endphp
                                @foreach($categories as $cat)
                                    <a href="{{ route('categories.show', $cat->slug) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600">{{ $cat->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 max-w-lg mx-4 hidden md:block">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input type="text" name="q" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </form>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-indigo-600">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @php
    use App\Models\Cart;
    $cartCount = 0;
    $cart = auth()->check()
        ? Cart::where('user_id', auth()->id())->first()
        : Cart::where('session_id', session()->getId())->first();
    if ($cart) {
        $cartCount = (int) $cart->items()->sum('quantity');
    }
@endphp
<span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center cart-count">{{ $cartCount }}</span>
                    </a>
                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-1 text-gray-600 hover:text-indigo-600">
                                <i class="fas fa-user-circle text-xl"></i>
                                <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50">Profile</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50">Orders</a>
                                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50">Wishlist</a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50">Admin</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="ShopHub" class="h-8 mb-2">
                    <p class="text-gray-400">Your one-stop shop for everything.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white">Shop</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white">Cart</a></li>
                        <li><a href="{{ route('orders.index') }}" class="hover:text-white">Orders</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">FAQs</a></li>
                        <li><a href="#" class="hover:text-white">Shipping</a></li>
                        <li><a href="#" class="hover:text-white">Returns</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Follow Us</h4>
                    <div class="flex space-x-4 text-gray-400">
                        <a href="#" class="hover:text-white"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                &copy; {{ date('Y') }} ShopHub. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateCartCount() {
            fetch('/cart/count')
                .then(r => r.json())
                .then(data => {
                    document.querySelectorAll('.cart-count').forEach(el => {
                        el.textContent = data.count;
                    });
                })
                .catch(() => {});
        }
        document.querySelectorAll('form[action*="cart/add"]').forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(updateCartCount, 500);
            });
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
