@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center">
        <h3 class="text-2xl font-bold text-gray-800">Orders</h3>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                Pending: {{ $pendingCount }}
            </span>
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                Delivered: {{ $deliveredCount }}
            </span>
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                Canceled: {{ $canceledCount }}
            </span>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <select id="orderStatusFilter" 
                        onchange="filterOrders()"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                    <option value="all">All Orders</option>
                    <option value="pending">Pending</option>
                    <option value="delivered">Delivered</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>
            <div class="flex gap-2 w-full md:w-96">
                <input type="text" 
                       id="searchOrders" 
                       placeholder="Search by Customer Name" 
                       onkeyup="searchOrders()"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                <button onclick="searchOrders()"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th onclick="sortTable(0)" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">Order ID</th>
                        <th onclick="sortTable(1)" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">Customer</th>
                        <th onclick="sortTable(2)" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">Order Date</th>
                        <th onclick="sortTable(3)" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">Status</th>
                        <th onclick="sortTable(4)" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="orderTable" class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->created_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status == 'delivered' ? 'bg-green-100 text-green-800' : 
                                    'bg-red-100 text-red-800') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($order->total_price + 3, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewOrderDetails({{ $order->id }})"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                View
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links('components.pagination') }}
        </div>
    </div>
</div>

<script>
    function filterOrders() {
        var filter = document.getElementById("orderStatusFilter").value;
        var rows = document.getElementById("orderTable").getElementsByTagName("tr");
        for (var i = 0; i < rows.length; i++) {
            var status = rows[i].getElementsByTagName("td")[3].innerText.toLowerCase();
            if (filter === "all" || status === filter) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    function searchOrders() {
        var input = document.getElementById("searchOrders").value.toLowerCase();
        var rows = document.getElementById("orderTable").getElementsByTagName("tr");
        for (var i = 0; i < rows.length; i++) {
            var customer = rows[i].getElementsByTagName("td")[1].innerText.toLowerCase();
            if (customer.includes(input)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    function sortTable(n) {
        var table = document.getElementById("orderTable");
        var rows = Array.from(table.getElementsByTagName("tr"));
        var sortedRows = rows.sort(function(a, b) {
            var x = a.getElementsByTagName("td")[n].innerText.toLowerCase();
            var y = b.getElementsByTagName("td")[n].innerText.toLowerCase();
            return x.localeCompare(y);
        });
        for (var i = 0; i < sortedRows.length; i++) {
            table.appendChild(sortedRows[i]);
        }
    }

    function viewOrderDetails(id) {
        window.location.href = `/admin/orders/details/${id}`;
    }
</script>

@endsection
