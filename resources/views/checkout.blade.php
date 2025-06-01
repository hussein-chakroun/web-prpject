@extends('layouts.master')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Billing Address Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Billing Address</h2>
                
                <form action="{{ route('placeOrder') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Address Input -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Address</label>
                        <div class="mt-1">
                            <input 
                                type="text" 
                                id="location" 
                                name="location" 
                                value="{{ old('location') }}" 
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-500 focus:border-gray-500" 
                                placeholder="Enter your full address" 
                                required>
                        </div>
                    </div>

                    <!-- Phone Input -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="mt-1">
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-500 focus:border-gray-500" 
                                placeholder="Enter your phone number" 
                                required>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Place Order
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
                
                <!-- Order Items -->
                <div class="space-y-4 mb-6">
                    @foreach ($cartItems as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">${{ number_format($item->quantity * $item->product->price, 2) }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- Order Totals -->
                <div class="space-y-2 border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">Subtotal</p>
                        <p class="font-medium text-gray-900">${{ number_format($subtotal, 2) }}</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">Shipping</p>
                        <p class="font-medium text-gray-900">${{ number_format($shippingCost, 2) }}</p>
                    </div>
                    <div class="flex justify-between text-base font-medium border-t border-gray-200 pt-4 mt-4">
                        <p class="text-gray-900">Total</p>
                        <p class="text-gray-900">${{ number_format($totalPrice, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
