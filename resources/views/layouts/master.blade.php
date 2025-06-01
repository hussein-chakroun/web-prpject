<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Store')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col h-full bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-[1920px] mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home.index') }}" class="text-xl font-bold text-gray-900">Khat-Husseini</a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden items-center space-x-8 md:flex">
                    <a href="{{ route('home.index') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900">Products</a>
                    <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900">Categories</a>
                    <a href="/about" class="text-gray-600 hover:text-gray-900">About Us</a>
                </div>

                <!-- Right Side Navigation -->
                <div class="flex items-center space-x-4">
                    <!-- Cart -->
                    @auth
                        <a href="{{ route('cart.view') }}" class="relative text-gray-600 hover:text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if(session('cart'))
                                @php
                                    $totalItems = 0;
                                    foreach(session('cart') as $item) {
                                        $totalItems += $item['quantity'];
                                    }
                                @endphp
                                @if($totalItems > 0)
                                    <span class="flex absolute -top-2 -right-2 justify-center items-center w-5 h-5 text-xs text-white bg-gray-900 rounded-full">
                                        {{ $totalItems }}
                                    </span>
                                @endif
                            @endif
                        </a>

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-600 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 py-1 mt-2 w-48 bg-white rounded-md shadow-lg">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="{{ route('user.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block px-4 py-2 w-full text-sm text-left text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-[1920px] mx-auto w-full px-6 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-8 bg-white shadow-sm">
        <div class="max-w-[1920px] mx-auto px-6 py-4">
            <p class="text-center text-gray-600">&copy; 2024 My Store. All rights reserved.</p>
        </div>
    </footer>

    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
