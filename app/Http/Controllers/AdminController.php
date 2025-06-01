<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Order_items;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showMetrics(Request $request)
{
    // Filter by date range (optional)
    $startDate = $request->input('start_date') 
        ? Carbon::parse($request->input('start_date')) 
        : Carbon::now()->startOfMonth();

    $endDate = $request->input('end_date') 
        ? Carbon::parse($request->input('end_date')) 
        : Carbon::now()->endOfMonth();

    // Revenue and Profit (Delivered Orders)
    $deliveredOrders = Order::where('status', 'delivered')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

    $totalRevenue = $deliveredOrders->sum('total_price'); // Assuming total_price column exists in the orders table

    $totalProfit = $deliveredOrders->sum(function ($order) {
        $orderItems = Order_items::where('order_id', $order->id)
            ->with('product')
            ->get();

        return $orderItems->sum(function ($item) {
            $productCost = $item->product->cost ?? 0;
            $productPrice = $item->product->price ?? 0;
            return ($productPrice - $productCost) * $item->quantity;
        });
    });

    // Order Status Overview
    $ordersByStatus = Order::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->get()
        ->keyBy('status');

    // Best-Selling Products
    $bestSellingProducts = Order_items::whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate]);
    })
    ->with('product')
    ->select('product_id')
    ->selectRaw('SUM(quantity) as total_sold')
    ->groupBy('product_id')
    ->orderByDesc('total_sold')
    ->take(5)
    ->get();

    return view('admin.profit', compact(
        'totalRevenue',
        'totalProfit',
        'ordersByStatus',
        'bestSellingProducts',
        'startDate',
        'endDate'
    ));
}




public function dashboard()
{
    // Total Sales
    $totalSales = Order::where('status', 'delivered')->sum('total_price');

    // Total Orders
    $totalOrders = Order::count();

    // Total Products
    $totalProducts = Product::count();

    // Total Customers
    $totalCustomers = User::count();

    // Monthly Sales Data for Chart
    $monthlySales = Order::where('status', 'delivered')
        ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
        ->groupBy('month')
        ->pluck('total', 'month'); // Correct pluck

    $salesData = [];
    for ($i = 1; $i <= 12; $i++) {
        $salesData[] = $monthlySales[$i] ?? 0; // Use $monthlySales array correctly
    }

    // Top 5 Most Sold Products
    $topProducts = Product::join('order_items', 'products.id', '=', 'order_items.product_id')
        ->select('products.name')
        ->selectRaw('SUM(order_items.quantity) as total_sold')
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total_sold')
        ->take(5)
        ->get();

    // Recent Orders
    $recentOrders = Order::with('user') // Assuming an Order belongs to a user
        ->latest()
        ->take(5)
        ->get();

    return view('admin.dashboard', [
        'totalSales' => $totalSales ?? 0,
        'totalOrders' => $totalOrders ?? 0,
        'totalProducts' => $totalProducts ?? 0,
        'totalCustomers' => $totalCustomers ?? 0,
        'salesData' => $salesData,
        'topProducts' => $topProducts,
        'recentOrders' => $recentOrders,
    ]);
}

}



