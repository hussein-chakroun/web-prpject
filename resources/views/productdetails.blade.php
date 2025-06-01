@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Single Product -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="relative">
                <img src="data:image/jpeg;base64,{{ $product->image }}" 
                    alt="{{ $product->name }}"
                    class="w-full h-[400px] object-cover rounded-lg">
            </div>

            <!-- Product Details -->
            <div class="flex flex-col">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                
                <div class="mb-4">
                    <span class="text-sm text-gray-600">Category:</span>
                    <span class="text-gray-800 font-medium ml-2">
                        {{ $categoryMap[$product->category_id] ?? 'Unknown' }}
                    </span>
                </div>

                <div class="text-2xl font-bold text-gray-800 mb-6">
                    ${{ number_format($product->price, 1) }}
                </div>

                <p class="text-gray-600 mb-8">{{ $product->description }}</p>

                <!-- Add to Cart Form -->
                <div class="mt-auto">
                    @if (Auth::check())
                        <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity:</label>
                                <input type="number" 
                                    id="quantity" 
                                    name="quantity" 
                                    value="1" 
                                    min="1"
                                    max="{{ $product->quantity }}" 
                                    required
                                    class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                            </div>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" 
                                class="w-full px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <a href="/login" 
                            class="block w-full px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                            Add to Cart
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-16">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Related Products
            </h2>
            <p class="text-gray-600">
                More Products From {{ $categoryMap[$product->category_id] ?? 'Unknown' }} Category
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products->where('category_id', $product->category_id)->take(3) as $relatedProduct)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4">
                    <div class="aspect-w-1 aspect-h-1 mb-4">
                        <a href="{{ route('products.show', $relatedProduct->id) }}">
                            <img src="data:image/jpeg;base64,{{ $relatedProduct->image }}" 
                                alt="{{ $relatedProduct->name }}"
                                class="w-full h-48 object-cover rounded-lg">
                        </a>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate">{{ $relatedProduct->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2">
                        {{ $categoryMap[$relatedProduct->category_id] ?? 'Unknown' }}
                    </p>
                    <p class="text-lg font-bold text-gray-800 mb-4">
                        ${{ number_format($relatedProduct->price, 1) }}
                    </p>
                    <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
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
    </div>
</div>

<script>
    function showLogin() {
        window.location.href = '/login';
    }
</script>
@endsection
