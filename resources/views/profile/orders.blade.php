@extends('layouts.master')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h2>

    <!-- Display Success or Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col space-y-8">
        <!-- Current Orders Section -->
        <div>
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Current Orders</h4>
            @php
                $currentOrders = $orders->filter(fn($order) => $order->status == 'pending');
            @endphp
            @if($currentOrders->isNotEmpty())
                <div class="space-y-4">
                    @foreach($currentOrders as $order)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200">
                            <div class="p-6">
                                <div class="flex flex-col space-y-4">
                                    <!-- Order Summary -->
                                    <div class="space-y-2">
                                        <h6 class="text-sm font-medium text-gray-900">Order ID: <span class="text-gray-900">#{{ $order->id }}</span></h6>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Status:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Date:</span> {{ $order->created_at->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Total:</span> ${{ number_format($order->total_price + 3, 2) }}</p>
                                    </div>
                                    <!-- Cancel Button -->
                                    <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Cancel Order
                                        </button>
                                    </form>
                                </div>
                                <!-- Order Items -->
                                <div class="mt-4">
                                    <h6 class="text-sm font-medium text-gray-900 mb-2">Items</h6>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        @foreach($order->items as $item)
                                            <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                                                <span class="text-sm text-gray-600">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                                <span class="text-sm font-medium text-gray-900">${{ number_format($item->price, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">You have no current orders.</p>
            @endif
        </div>

        <!-- Completed Orders Section -->
        <div>
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Completed Orders</h4>
            @php
                $completedOrders = $orders->filter(fn($order) => $order->status == 'delivered');
            @endphp
            @if($completedOrders->isNotEmpty())
                <div class="space-y-4">
                    @foreach($completedOrders as $order)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200">
                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Order Summary -->
                                    <div class="space-y-2">
                                        <h6 class="text-sm font-medium text-gray-900">Order ID: <span class="text-gray-900">#{{ $order->id }}</span></h6>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Status:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Date:</span> {{ $order->created_at->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Total:</span> ${{ number_format($order->total_price + 3, 2) }}</p>
                                    </div>
                                </div>
                                <!-- Order Items -->
                                <div class="mt-4">
                                    <h6 class="text-sm font-medium text-gray-900 mb-2">Items</h6>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        @foreach($order->items as $item)
                                            <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                                                <span class="text-sm text-gray-600">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                                <span class="text-sm font-medium text-gray-900">${{ number_format($item->price, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">You have no completed orders.</p>
            @endif
        </div>

        <!-- Canceled Orders Section -->
        <div>
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Canceled Orders</h4>
            @php
                $canceledOrders = $orders->filter(fn($order) => $order->status == 'canceled');
            @endphp
            @if($canceledOrders->isNotEmpty())
                <div class="space-y-4">
                    @foreach($canceledOrders as $order)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200">
                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Order Summary -->
                                    <div class="space-y-2">
                                        <h6 class="text-sm font-medium text-gray-900">Order ID: <span class="text-gray-900">#{{ $order->id }}</span></h6>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Status:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Date:</span> {{ $order->created_at->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Total:</span> ${{ number_format($order->total_price + 3, 2) }}</p>
                                    </div>
                                </div>
                                <!-- Order Items -->
                                <div class="mt-4">
                                    <h6 class="text-sm font-medium text-gray-900 mb-2">Items</h6>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        @foreach($order->items as $item)
                                            <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                                                <span class="text-sm text-gray-600">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                                <span class="text-sm font-medium text-gray-900">${{ number_format($item->price, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">You have no canceled orders.</p>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $orders->links('components.pagination') }}
    </div>
</div>
@endsection
