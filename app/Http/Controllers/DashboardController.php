<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // function trang dashboard
    public function index()
    {
        // tổng doanh thu
        $totalRevenue = Order::where('status', 'completed')
            ->with('items')
            ->get()
            ->flatMap->items
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });
     
        // doanh thu theo từng sản phẩm
        $topProducts = DB::table('orders')
        ->join('order_items', 'orders.id', '=', 'order_items.order_id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->where('orders.status', 'completed')
        ->select(
            'products.id',
            'products.name as product_name',
            'categories.name as category_name',
            DB::raw('SUM(order_items.quantity) as total_sold'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
        )
        ->groupBy('products.id', 'products.name', 'categories.name')
        ->orderByDesc('total_revenue') // xếp theo tổng doanh thu
        ->limit(5) // chỉ lấy 5 sản phẩm
        ->get();
        
        // tổng số sp trong kho
        $totalProducts = Product::count();

        // tổng đơn hàng
        $totalOrders = Order::count();

        // đơn hàng mới
        $latestOrders = Order::with('user')
        ->latest()
        ->take(5)
        ->get();

        // tổng khách hàng
        $totalUsers = User::count();

        return view('admins.dashboard', compact('totalRevenue', 'topProducts', 'totalProducts', 'totalOrders', 'latestOrders', 'totalUsers'));
    }
}
