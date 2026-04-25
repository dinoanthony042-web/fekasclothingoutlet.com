<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display sales report dashboard.
     */
    public function sales(Request $request): View
    {
        $from_date = $request->from_date ? Carbon::createFromFormat('Y-m-d', $request->from_date) : Carbon::now()->subMonth();
        $to_date = $request->to_date ? Carbon::createFromFormat('Y-m-d', $request->to_date) : Carbon::now();

        // Total sales
        $totalSales = Order::whereBetween('created_at', [$from_date, $to_date])
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        // Total orders
        $totalOrders = Order::whereBetween('created_at', [$from_date, $to_date])
            ->where('status', '!=', 'cancelled')
            ->count();

        // Average order value
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Top products by sales
        $topProducts = OrderItem::select('product_id', DB::raw('sum(quantity) as total_quantity'), DB::raw('sum(price * quantity) as total_sales'))
            ->whereHas('order', function ($q) use ($from_date, $to_date) {
                $q->whereBetween('created_at', [$from_date, $to_date])
                    ->where('status', '!=', 'cancelled');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->with('product')
            ->limit(10)
            ->get();

        // Sales by date (for chart)
        $salesByDate = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(total) as total'))
            ->whereBetween('created_at', [$from_date, $to_date])
            ->where('status', '!=', 'cancelled')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Order status distribution
        $orderStatusDistribution = Order::whereBetween('created_at', [$from_date, $to_date])
            ->groupBy('status')
            ->select('status', DB::raw('count(*) as count'))
            ->get();

        return view('admin.reports.sales', compact(
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'topProducts',
            'salesByDate',
            'orderStatusDistribution',
            'from_date',
            'to_date'
        ));
    }

    /**
     * Display product report.
     */
    public function products(Request $request): View
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Sort by different criteria
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';

        // Get products with sales count and revenue
        $products = Product::with('category')
            ->select('products.*', DB::raw('COALESCE(COUNT(order_items.id), 0) as total_sales'), DB::raw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue'))
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.description', 'products.price', 'products.category_id', 'products.sizes', 'products.colors', 'products.styles', 'products.images', 'products.stock', 'products.is_featured', 'products.is_new', 'products.is_best_seller', 'products.age_range', 'products.created_at', 'products.updated_at')
            ->orderBy($sort === 'sales' ? 'total_sales' : ($sort === 'revenue' ? 'total_revenue' : 'products.' . $sort), $direction)
            ->paginate(20);

        return view('admin.reports.products', compact('products'));
    }

    /**
     * Display customer report.
     */
    public function customers(Request $request): View
    {
        $customers = \App\Models\User::select('users.*', DB::raw('COUNT(orders.id) as total_orders'), DB::raw('SUM(orders.total) as total_spent'))
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->where('users.role', '!=', 'admin')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.phone', 'users.password', 'users.remember_token', 'users.email_verified_at', 'users.role', 'users.created_at', 'users.updated_at')
            ->orderByDesc('total_spent')
            ->paginate(20);

        return view('admin.reports.customers', compact('customers'));
    }

    /**
     * Export sales report as CSV.
     */
    public function exportSales(Request $request)
    {
        $from_date = $request->from_date ? Carbon::createFromFormat('Y-m-d', $request->from_date) : Carbon::now()->subMonth();
        $to_date = $request->to_date ? Carbon::createFromFormat('Y-m-d', $request->to_date) : Carbon::now();

        $orders = Order::with('user', 'items.product')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->where('status', '!=', 'cancelled')
            ->get();

        $filename = 'sales_report_' . now()->format('Y_m_d') . '.csv';
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        );

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Customer', 'Email', 'Total', 'Status', 'Date']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name,
                    $order->user->email,
                    $order->total,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
