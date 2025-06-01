@extends('layouts.master')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Cart Items -->
        <div class="lg:w-2/3">
            @if($cart && $cartItems->count() > 0)
                <div class="bg-white rounded-lg border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-600">Product</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-600">Price</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-600">Quantity</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-600">Total</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-600"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <img src="data:image/jpeg;base64,{{ $item->product->image }}" 
                                                    alt="{{ $item->product->name }}"
                                                    class="w-16 h-16 object-cover rounded-lg">
                                                <span class="text-sm text-gray-900">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            ${{ number_format($item->product->price ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                        min="1" max="10"
                                                        class="w-16 px-2 py-1 border border-gray-300 rounded-lg text-sm">
                                                    <button type="submit" class="text-gray-600 hover:text-gray-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            ${{ number_format(($item->quantity * ($item->product->price ?? 0)), 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg border border-gray-100 p-8 text-center">
                    <p class="text-gray-600 mb-4">Your cart is empty</p>
                    <a href="{{ route('products.index') }}" class="inline-block px-6 py-2 bg-gray-900 text-white rounded-lg">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-lg border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900">${{ number_format($cartItems->sum(fn($item) => $item->quantity * $item->product->price), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Delivery</span>
                        <span class="text-gray-900">$3.00</span>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex justify-between font-semibold">
                            <span class="text-gray-900">Total</span>
                            <span class="text-gray-900">${{ number_format($cartItems->sum(fn($item) => $item->quantity * $item->product->price) + 3, 2) }}</span>
                        </div>
                    </div>
                </div>
                @if($cartItems->count() > 0)
                    <a href="{{ route('checkout') }}" class="mt-6 block w-full px-6 py-3 bg-gray-900 text-white text-center rounded-lg">
                        Proceed to Checkout
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
