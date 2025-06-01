@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Search Bar -->
    <div class="mb-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex gap-2 max-w-2xl mx-auto">
            <input type="text" name="search" 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                placeholder="Search for products..."
                value="{{ request('search') }}">
            <button type="submit" 
                class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                Search
            </button>
        </form>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Filters</h2>
                
                <!-- Category Filters -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Categories</h3>
                    <div class="space-y-2">
                        <a href="{{ route('products.index') }}" 
                            class="block px-3 py-2 rounded-lg {{ request('category') ? 'text-gray-600 hover:bg-gray-50' : 'bg-gray-100 text-gray-800' }} transition-colors">
                            All
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                class="block px-3 py-2 rounded-lg {{ request('category') == $category->id ? 'bg-gray-100 text-gray-800' : 'text-gray-600 hover:bg-gray-50' }} transition-colors">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Price Filter -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Price Range</h3>
                    <form method="GET" action="{{ route('products.index') }}" class="space-y-3">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="text" name="search" value="{{ request('search') }}" hidden>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Min Price</label>
                            <input type="number" name="min_price" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                                placeholder="Min" 
                                value="{{ request('min_price') }}">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Max Price</label>
                            <input type="number" name="max_price" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                                placeholder="Max" 
                                value="{{ request('max_price') }}">
                        </div>
                        <button type="submit" 
                            class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4">
                        <div class="aspect-w-1 aspect-h-1 mb-4">
                            <a href="{{ route('products.show', $product->id) }}">
                                <img src="data:image/jpeg;base64,{{ $product->image }}" 
                                    alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover rounded-lg">
                            </a>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $categoryMap[$product->id] ?? 'Unknown' }}
                        </p>
                        <p class="text-lg font-bold text-gray-800 mb-4">
                            ${{ number_format($product->price, 1) }}
                        </p>
                        <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            @if (Auth::check())
                                <button type="submit" 
                                    class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                    </svg>
                                    Add to Cart
                                </button>
                            @else
                                <button type="button" 
                                    onclick="showLogin()"
                                    class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                    </svg>
                                    Add to Cart
                                </button>
                            @endif
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links('components.pagination') }}
            </div>
        </div>
    </div>
</div>

<script>
    function showLogin() {
        window.location.href = '/login';
    }
</script>
@endsection
