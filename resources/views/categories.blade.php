@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Product Categories</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Discover beautiful paintings of revered Shia figures including Imam Ali, Imam Hussein, and Sayed Hassan. Each piece is crafted with devotion and spiritual significance.        </p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($categories as $item)
            <a href="{{ route('products.index', ['category' => $item->id]) }}" class="group">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 h-full">
                    <div class="relative overflow-hidden rounded-t-lg">
                        <img src="data:image/jpeg;base64,{{ $item->image }}"
                            alt="{{ $item->name }}"
                            class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2 group-hover:text-gray-900 transition-colors">
                            {{ $item->name }}
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-2">
                            {{ $item->description }}
                        </p>
                        <div class="mt-4 flex items-center text-gray-800 group-hover:text-gray-900">
                            <span class="text-sm font-medium">View Products</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
