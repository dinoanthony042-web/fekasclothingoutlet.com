<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(): View
    {
        // Key metrics
        $totalProducts = Product::count();
        $totalCategories = Category::whereNull('parent_id')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalUsers = User::where('role', '!=', 'admin')->count();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // Recent products
        $recentProducts = Product::with('category')->latest()->take(8)->get();

        // Top selling products
        $topProducts = Product::select('products.*', DB::raw('COUNT(order_items.id) as total_sales'), DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.description', 'products.price', 'products.category_id', 'products.sizes', 'products.colors', 'products.styles', 'products.images', 'products.stock', 'products.is_featured', 'products.is_new', 'products.is_best_seller', 'products.age_range', 'products.created_at', 'products.updated_at')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        // Sales this month
        $thisMonthSales = Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        // Pending orders
        $pendingOrders = Order::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'recentOrders',
            'recentProducts',
            'topProducts',
            'thisMonthSales',
            'pendingOrders'
        ));
    }
}
