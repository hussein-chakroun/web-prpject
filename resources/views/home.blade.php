@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="bg-gray-50 rounded-lg p-8 md:p-12 mb-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">
                Welcome to Our Store
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                Discover quality products at great prices
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#products" class="px-6 py-3 bg-gray-900 text-white rounded-lg">
                    Shop Now
                </a>
                <a href="{{ route('categories.index') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-900 rounded-lg">
                    Browse Categories
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Fast Delivery</h3>
                    <p class="text-gray-600">Same day Delivery</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">24/7 Support</h3>
                    <p class="text-gray-600">Get support all day</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Easy to Use</h3>
                    <p class="text-gray-600">Place an order with ease</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div id="products" class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Explore Our Latest Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products->random(6) as $product)
                <div class="bg-white border border-gray-100 p-4">
                    <div class="aspect-w-1 aspect-h-1 mb-4">
                        <a href="{{ route('products.show', $product->id) }}">
                            <img src="data:image/jpeg;base64,{{ $product->image }}" 
                                alt="{{ $product->name }}"
                                class="w-full h-48 object-cover rounded-lg">
                        </a>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2 truncate">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2">
                        {{ $categoryMap[$product->category_id] ?? 'Unknown' }}
                    </p>
                    <p class="text-lg font-bold text-gray-900 mb-4">
                        ${{ number_format($product->price, 1) }}
                    </p>
                    <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        @if (Auth::check())
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-gray-900 text-white rounded-lg">
                                Add to Cart
                            </button>
                        @else
                            <button type="button" 
                                onclick="showLogin()"
                                class="w-full px-4 py-2 bg-gray-900 text-white rounded-lg">
                                Add to Cart
                            </button>
                        @endif
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function showLogin() {
        window.location.href = '/login';
    }
</script>
@endsection
